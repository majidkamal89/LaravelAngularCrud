@extends('admin.layouts.manager')

@section('header')
        <h1>
            Customers
        </h1>
@endsection
@section('content')
    @include('alerts.Alerts')
    <div class="row" ng-app="customerApp" ng-controller="customerController" ng-cloak>
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
                                <label class="control-label col-sm-4">Search:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" ng-model="search">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-2 text-right">
                        <button type="button" ng-click="loadModal()" class="btn btn-primary btn-sm">Add New Customer</button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th ng-click="sort('customer_name')" class="cursor-pointer">Customer Name
                                    <span class="glyphicon sort-icon" ng-show="sortKey=='customer_name'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                </th>
                                <th ng-click="sort('customer_address')" class="cursor-pointer">Address
                                    <span class="glyphicon sort-icon" ng-show="sortKey=='customer_address'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                </th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr dir-paginate="record in Customers|orderBy:sortKey:reverse|filter:search|itemsPerPage:10">
                                <td>@{{ record.customer_name }}</td>
                                <td>@{{ record.customer_address }}</td>
                                <td>@{{ record.created_at }}</td>
                                <td style="width: 15%;">
                                    <button type="button" class="btn btn-small btn-xs btn-warning" ng-click="editAction(record)">Edit</button> |
                                    <button type="button" class="btn btn-small btn-xs btn-danger" ng-click="deleteModal(record.id)">Delete</button> |
                                    <a href="{{ URL::to('/project/') }}/@{{ record.id }}" class="btn btn-small btn-xs btn-success">Projects</a>
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
                <!-- Add/update Modal -->
                <div id="addCustomer" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content border-radius">
                            <div class="modal-header">
                                <div class="col-md-1 loader" ng-if="isDisabled"><img src="{{ asset('admin/dist/img/loading.gif') }}" alt="Data uploading...." /></div>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" ng-if="newData.id">Update Customer</h4>
                                <h4 class="modal-title" ng-if="!newData.id">Add New Customer</h4>
                            </div>
                            <div class="modal-body">
                                <form ng-submit="submitForm()" class="form-horizontal" enctype="multipart/form-data">
                                    <input type="hidden" name="id" ng-model="newData.id" value="" />
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="email">Customer Name:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="customer_name" ng-model="newData.customer_name" placeholder="Customer Name" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Address:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="customer_address" ng-model="newData.customer_address" placeholder="Customer Address">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Signature:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="signature" ng-model="newData.signature" placeholder="Signature">
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
                <!--End Add Modal -->
                <!-- Delete Modal -->
                <div id="confirmDelete" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Delete</h4>
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

            </div>
            <!-- /.box -->

            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/customerapp.js') }}"></script>
    <script src="{{ asset('/js/dirPagination.js') }}"></script>
    <script>myApp.constant("CSRF_TOKEN", '{!! csrf_token() !!}');</script>
    <script src="{{ asset('/js/controller/customerController.js') }}"></script>
    <script src="{{ asset('/js/service/customerService.js') }}"></script>
@endsection