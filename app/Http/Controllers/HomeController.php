<?php

namespace App\Http\Controllers;

use App\Customer;
use App\User;
use App\Project;
use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::getUser()->user_type == 1){
            $allUsers = User::where('user_type', '<>', 1)->get();
            $managers = count($allUsers->where('user_type', 2));
            $drivers = count($allUsers->where('user_type', 3));
            $customers = Customer::get()->count();
            $projects = Project::get()->count();
            $companies = Company::get()->count();
            return View::make('admin.index', compact('managers','drivers','customers','projects','companies'));
        } elseif(Auth::getUser()->user_type == 2){
            $company_users = User::where('company_id', Auth::getUser()->company_id)->where('user_type', 3)->get()->count();
            $customers = Customer::where('user_id', Auth::getUser()->id)->get()->count();
            $projects = Project::where('user_id', Auth::getUser()->id)->get()->count();
            return View::make('admin.manager_index', compact('company_users','customers','projects'));
        } elseif(Auth::getUser()->user_type == 3){
            return View::make('admin.driver');
        }


    }
}
