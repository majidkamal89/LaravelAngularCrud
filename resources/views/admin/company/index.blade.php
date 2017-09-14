@extends('admin.layouts.app')

@section('header')
        <div class="row">
            <div class="col-md-4">
                <h1>
                    Companies
                </h1>
            </div>
            <div class="col-md-4">
                @include('alerts.Alerts')
            </div>
        </div>
@endsection
@section('content')
    <div class="row" ng-app="companyApp" ng-controller="companyController" ng-cloak>
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="col-md-1 data-loader" ng-if="isDisabled"><img src="{{ asset('admin/dist/img/loading.gif') }}" alt="Data uploading...." /></div>
                    <div class="col-md-2"></div>
                    <div class="col-md-4">
                        <div class="alert alert-success text-center custom-margin" ng-if="notification">
                            @{{ message }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-sm-4"> Search:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" ng-model="search">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-2 text-right">
                        <button type="button" ng-click="loadModal()" class="btn btn-primary btn-sm">Add New Company</button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th ng-click="sort('company_name')" class="cursor-pointer">Company Name
                                    <span class="glyphicon sort-icon" ng-show="sortKey=='company_name'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                </th>
                                <th ng-click="sort('address')" class="cursor-pointer">Address
                                    <span class="glyphicon sort-icon" ng-show="sortKey=='address'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                </th>
                                <th>Zip</th>
                                <th>City</th>
                                <th>Logo</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr dir-paginate="record in Companies|orderBy:sortKey:reverse|filter:search|itemsPerPage:10">
                                <td>@{{ record.company_name }}</td>
                                <td>@{{ record.address }}</td>
                                <td>@{{ record.zip_postal }}</td>
                                <td>@{{ record.city }}</td>
                                <td><img src="{{ url('uploads') }}/@{{ record.logo }}" alt="No File" class="row-img-size" /></td>
                                <td>@{{ record.created_at }}</td>
                                <td style="width: 20%;">
                                    <button type="button" class="btn btn-small btn-xs btn-warning" ng-click="editAction(record)">Edit</button> |
                                    <button type="button" class="btn btn-small btn-xs btn-danger" ng-click="deleteModal(record.id)">Delete</button> |
                                    <a href="{{ URL::to('/edit/user') }}/@{{ record.id }}" class="btn btn-small btn-xs btn-success" ng-if="record.companyUser > 0">Edit Manager</a>
                                    <a href="{{ URL::to('/create/user') }}/@{{ record.id }}" class="btn btn-small btn-xs btn-default" ng-if="record.companyUser == 0">Add Manager</a>
                                    <a href="{{ URL::to('/users/impersonate/') }}/@{{ record.companyUser }}" class="btn btn-small btn-xs btn-info" ng-if="record.companyUser > 0">Login</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <dir-pagination-controls
                                max-size="100"
                                direction-links="true"
                                boundary-links="true" >
                        </dir-pagination-controls>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            <!-- Add/update Modal -->
            <div id="addCompany" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content border-radius">
                        <div class="modal-header">
                            <div class="col-md-1 loader" ng-if="isDisabled"><img src="{{ asset('admin/dist/img/loading.gif') }}" alt="Data uploading...." /></div>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" ng-if="newData.id">Update Company</h4>
                            <h4 class="modal-title" ng-if="!newData.id">Add New Company</h4>
                        </div>
                        <div class="modal-body">
                            <form ng-submit="submitForm(newData.logo)" class="form-horizontal" enctype="multipart/form-data">
                                <input type="hidden" name="id" ng-model="newData.id" value="" />
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="email">Company Name:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="company_name" ng-model="newData.company_name" placeholder="Company Name" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Address:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="address" ng-model="newData.address" placeholder="Address">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Zip:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control search-field" name="zip" ng-model="newData.zip_postal" placeholder="Zip Code">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">City:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="city" ng-model="newData.city" placeholder="City">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Logo:</label>
                                    <div class="col-sm-8">
                                        <input ng-if="!newData.id" type="file" ngf-select ng-model="newData.logo" name="logo"
                                               accept="image/*" ngf-max-size="3MB" ngf-model-invalid="newData.errorFile" required>
                                        <input ng-if="newData.id" type="file" ngf-select ng-model="newData.logo" name="logo"
                                               accept="image/*" ngf-max-size="3MB" ngf-model-invalid="newData.errorFile">
                                        <i ng-show="newData.errorFile" class="alert-danger">Max Allowed file size is 3MB.</i>
                                        <div class="col-sm-12 pad">
                                            <img ng-show="newData.logo.$valid" ngf-thumbnail="newData.logo" style="width: 300px;" class="thumb">
                                        </div>
                                        <div class="col-sm-12 pad">
                                            <button ng-if="!newData.id" class="btn btn-default btn-small" ng-click="newData.logo = null" ng-show="newData.logo">Remove</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" ng-if="newData.id" ng-disabled="isDisabled" class="btn btn-success">Update</button>
                                        <button type="submit" ng-if="!newData.id" ng-disabled="isDisabled" class="btn btn-primary">Submit</button>
                                        <button type="button" class="btn btn-danger" ng-click="closeForm()">Cancel</button>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="alert @{{ alert_class }} text-center custom-margin" ng-if="isResponse">
                                            @{{ message }}
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Delete Modal -->
            <div id="confirmDelete" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Delete Company</h4>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure, You want to delete this Record?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                            <button type="button" class="btn btn-danger" ng-click="deleteAction(delete_id)">Yes</button>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/ng-file-upload-shim.min.js') }}"></script>
    <script src="{{ asset('/js/ng-file-upload.min.js') }}"></script>
    <script src="{{ asset('/js/app.js') }}"></script>
    <script src="{{ asset('/js/dirPagination.js') }}"></script>
    <script>
        myApp.constant("CSRF_TOKEN", '{!! csrf_token() !!}');
        myApp.constant("IMG_PATH", '{!! url('uploads').'/' !!}');
        $(".search-field").numeric();
        setTimeout(function(){
            $('.alert-dismissable').remove();
        }, 3000);
    </script>
    <script src="{{ asset('/js/controller/companyController.js') }}"></script>
    <script src="{{ asset('/js/service/companyService.js') }}"></script>
@endsection