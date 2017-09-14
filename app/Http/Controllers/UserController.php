<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_type = 'Company User';
        return View::make('admin.users.index', compact('user_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $company_id = $id;
        $company = Company::where('id', $company_id)->first(['company_name']);
        $company_name = $company->company_name;
        $user_type = 2;
        return View::make('admin.users.create', compact('company_id','company_name','user_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!empty($request->id) && empty($request->password)){
            $rules = [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required'
            ];
        } else {
            $rules = [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'password' => 'required|string|min:4|confirmed',
            ];
        }

        $message = [
            'first_name.required' => 'First Name field is required.',
            'last_name.required' => 'Last Name field is required.',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails()){
            if($request->wantsJson()){
                return Response()->json(['error' => $validator->errors()->first()]);
            }
            return Redirect::back()->withInput()->withErrors($validator);
        } else {
            if($request->wantsJson()){

                if(session()->has('impersonate')){
                    $company = User::where('id', session()->get('impersonate'))->first(['company_id']);
                    $company_id = $company->company_id;
                } else {
                    $company_id = Auth::getUser()->company_id;
                }
                $request->request->add(['company_id' => $company_id]);
            }
            try{
                if(!empty($request->id)){
                    if(empty($request->password)){
                        $createUser = User::where('id', $request->id)->update([
                            'first_name' => $request->first_name,
                            'last_name' => $request->last_name,
                            'email' => $request->email,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                    } else {
                        $createUser = User::where('id', $request->id)->update([
                            'first_name' => $request->first_name,
                            'last_name' => $request->last_name,
                            'email' => $request->email,
                            'password' => bcrypt($request->password),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                    if($request->wantsJson()){
                        return Response()->json(['success' => 'User information updated successfully!']);
                    }
                    return Redirect::route('company')->with('success', 'Company manager updated successfully!');
                }
                $createUser = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'company_id' => $request->company_id,
                    'user_type' => $request->user_type,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                if($request->wantsJson()){
                    return Response()->json(['success' => 'Company user created successfully!']);
                }
                return Redirect::route('company')->with('success', 'Company manager created successfully!');
            }
            catch(\Exception $e){
                if($request->wantsJson()){
                    return Response()->json(['error' => 'Sorry something went worng. Please try again.']);
                }
                return Redirect::route('company')->with('error', ' Sorry something went worng. Please try again.');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = Company::where('id', $id)->first();
        $company_name = $company->company_name;
        $user_type = 2;
        $userData = User::where('company_id', $id)->where('user_type', $user_type)->first();
        return View::make('admin.users.edit', compact('company_name','user_type','userData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            User::destroy($id);
            return Response()->json(array('success' => 'Record deleted successfully!'));
        } catch(\Exception $e){
            return Response()->json(['error' => 'Sorry something went worng. Please try again.']);
        }
    }

    /**
     * Fetch all drivers and return as Json.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllUser()
    {
        if(Auth::getUser()->user_type == 1) {
            return Response()->json(User::where('user_type', 3)->get());
        } else {
            return Response()->json(User::where('user_type', 3)->where('company_id', Auth::getUser()->company_id)->get());
        }
    }

    ////// Login As Manager/Super Admin
    public function impersonate($id)
    {
        $user = User::find($id);
        // Guard against administrator impersonate
        if($user->user_type == 2)
        {
            Auth::user()->setImpersonating($id);
            session()->flash('message', 'Manager '.$user->first_name.' '.$user->last_name.' login successfully.');
        }
        return Redirect::to('/manager');
    }

    public function stopImpersonate($user)
    {
        $userType = 'Manager';//($user == 2) ? 'Manager':'User';
        Auth::user()->stopImpersonating();
        session()->flash('message', ''.$userType.' logout successfully.');

        return Redirect::to('/companies');
    }
}
