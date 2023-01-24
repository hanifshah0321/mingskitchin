@extends('layouts.branch.app')

@section('title', translate('Add new table'))

@push('css_or_js')

@endpush
@section('content')
    <div class="content container-fluid">

        <div class="row align-items-center">
            <div class="col-sm mb-2 pb-5 mb-sm-0">
                <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i> {{translate('Add New Table')}}</h1>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('branch.table.store')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="number">{{translate('Table Number')}} <span class="text-danger">*</span></label>
                                <input type="number" name="number" class="form-control" id="number"
                                       placeholder="{{translate('Table Number')}}" value="{{old('number')}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">{{translate('Table Capacity')}} <span class="text-danger">*</span></label>
                                <input type="number" name="capacity" class="form-control" id="capacity"
                                       placeholder="{{translate('table Capacity')}}" min="1" max="99" value="{{old('capacity')}}" required>
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
                                                           onclick="location.href='{{route('branch.table.status',[$table['id'],$table->is_active?0:1])}}'"
                                                           class="toggle-switch-input" {{$table->is_active?'checked':''}}>
                                                    <span class="toggle-switch-label">
                                        <span class="toggle-switch-indicator"></span>
                                        </span>
                                                </label>
                                            </td>
                                            <td>
                                                <a href="{{route('branch.table.edit',[$table['id']])}}"
                                                   class="btn btn-primary btn-sm"
                                                   title="{{translate('Edit')}}">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-danger btn-sm" title="{{translate('Delete')}}" href="javascript:"
                                                   onclick="form_alert('table-{{$table['id']}}','{{translate('Want to delete this table ?')}}')">
                                                    <i class="tio-delete"></i>
                                                </a>
                                                <form action="{{route('branch.table.delete',[$table['id']])}}"
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

