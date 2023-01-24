@extends('layouts.branch.app')

@section('title', translate('Chef List'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="content container-fluid">
    <div class="pb-2">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class=""><i class=""></i> {{translate('Chef List')}}</h1>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 20px">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header flex-between">
                    <div class="">
                        <h5>{{translate('chef table')}}
                            <span style="color: red; padding: 0 .4375rem;">
                            ({{$chefs->total()}})</span>
                        </h5>
                    </div>
                    <div class="flex-end d-flex">
                        <div class="mx-2">
                            <form action="{{url()->current()}}" method="GET">
                                <div class="input-group">
                                    <input id="datatableSearch_" type="search" name="search"
                                           class="form-control"
                                           placeholder="{{translate('Search')}}" aria-label="Search"
                                           value="{{$search}}" required autocomplete="off">
                                    <div class="input-group-append">
                                        <button type="submit" class="input-group-text"><i class="tio-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div>
                            <a href="{{route('branch.kitchen.add-new')}}" class="btn btn-primary  float-right">
                                <i class="tio-add-circle"></i>
                                <span class="text">{{translate('Add')}} {{translate('New')}}</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="padding: 0">
                    <div class="table-responsive">
                        <table id="datatable" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                               class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               style="width: 100%">
                            <thead class="thead-light">
                            <tr>
                                <th>{{translate('SL')}}#</th>
                                <th>{{translate('Name')}}</th>
                                <th>{{translate('Email')}}</th>
                                <th>{{translate('Phone')}}</th>
                                <th>{{translate('Branch')}}</th>
                                <th>{{translate('Status')}}</th>
                                <th style="width: 50px">{{translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($chefs as $k=>$chef)
                                <tr>
                                    <th scope="row">{{$k+1}}</th>
                                    <td class="text-capitalize">{{$chef['f_name'] . ' ' . $chef['l_name']}}</td>
                                    <td>{{$chef['email']}}</td>
                                    <td>{{$chef['phone']}}</td>
                                    <td>{{ \App\User::get_chef_branch_name($chef) }}</td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm">
                                            <input type="checkbox" class="toggle-switch-input"
                                                   onclick="location.href='{{route('admin.kitchen.status',[$chef['id'],$chef->is_active?0:1])}}'"
                                                   class="toggle-switch-input" {{$chef->is_active?'checked':''}}>
                                            <span class="toggle-switch-label">
                                        <span class="toggle-switch-indicator"></span>
                                        </span>
                                        </label>
                                    </td>
                                    <td>
                                        <a href="{{route('branch.kitchen.edit',[$chef['id']])}}"
                                           class="btn btn-primary btn-sm"
                                           title="{{translate('Edit')}}">
                                           <i class="tio-edit"></i>
                                        </a>
                                        <a class="btn btn-danger btn-sm" title="{{translate('Delete')}}" href="javascript:"
                                           onclick="form_alert('chef-{{$chef['id']}}','{{translate('Want to delete this chef ?')}}')">
                                            <i class="tio-delete"></i>
                                        </a>
                                        <form action="{{route('branch.kitchen.delete',[$chef['id']])}}"
                                              method="post" id="chef-{{$chef['id']}}">
                                            @csrf @method('delete')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{$chefs->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
@endpush
