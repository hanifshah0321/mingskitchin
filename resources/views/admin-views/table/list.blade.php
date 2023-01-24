@extends('layouts.admin.app')

@section('title', translate('Add new table'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="pb-3">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class=""><i class="tio-add-circle-outlined"></i> {{translate('Add New Table')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.table.store')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="number">{{translate('Table Number')}} <span class="text-danger">*</span></label>
                                <input type="number" name="number" class="form-control" id="number"
                                       placeholder="{{translate('Ex')}} : {{translate('1')}}" value="{{old('number')}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">{{translate('Table Capacity')}} <span class="text-danger">*</span></label>
                                <input type="number" name="capacity" class="form-control" id="capacity"
                                       placeholder="{{translate('Ex')}} : {{translate('4')}}" min="1" max="99" value="{{old('capacity')}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlSelect1">{{translate('Select Branch')}} <span class="text-danger">*</span></label>
                                <select name="branch_id" class="form-control js-select2-custom" required>
                                    <option value="" selected>{{ translate('--select--') }}</option>
                                    @foreach($branches as $branch)
                                        <option value="{{$branch['id']}}">{{$branch['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                </form>
            </div>

            <div class="col-md-12 mt-2">
                    <div class="card">
                        <div class="card-header flex-between">
                            <div class="">
                                <h5>{{translate('table')}}
                                    <span style="color: red; padding: 0 .4375rem;">({{$tables->total()}})</span>
                                </h5>
                            </div>
                            <div class="flex-end">
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
                                        <th>{{translate('Table Number')}}</th>
                                        <th>{{translate('Table Capacity')}}</th>
                                        <th>{{translate('Branch')}}</th>
                                        <th>{{translate('Status')}}</th>
                                        <th style="width: 50px">{{translate('action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($tables as $k=>$table)
                                        <tr>
                                            <th scope="row">{{$k+1}}</th>
                                            <td>{{$table['number']}}</td>
                                            <td>{{$table['capacity']}}</td>
                                            <td>{{$table->branch->name ?? null}}</td>
                                            <td>
                                                <label class="toggle-switch toggle-switch-sm">
                                                    <input type="checkbox" class="toggle-switch-input"
                                                           onclick="location.href='{{route('admin.table.status',[$table['id'],$table->is_active?0:1])}}'"
                                                           class="toggle-switch-input" {{$table->is_active?'checked':''}}>
                                                    <span class="toggle-switch-label">
                                        <span class="toggle-switch-indicator"></span>
                                        </span>
                                                </label>
                                            </td>
                                            <td>
                                                <a href="{{route('admin.table.update',[$table['id']])}}"
                                                   class="btn btn-primary btn-sm"
                                                   title="{{translate('Edit')}}">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-danger btn-sm" title="{{translate('Delete')}}" href="javascript:"
                                                   onclick="form_alert('table-{{$table['id']}}','{{translate('Want to delete this table ?')}}')">
                                                    <i class="tio-delete"></i>
                                                </a>
                                                <form action="{{route('admin.table.delete',[$table['id']])}}"
                                                      method="post" id="table-{{$table['id']}}">
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
                            {{$tables->links()}}
                        </div>
                    </div>
                </div>

        </div>
    </div>

@endsection

@push('script')

@endpush

