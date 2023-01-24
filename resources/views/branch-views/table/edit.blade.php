@extends('layouts.branch.app')

@section('title', translate('Update table'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="pb-4">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class=""><i class="tio-add-circle-outlined"></i> {{translate('Update Table')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('branch.table.update',[$table['id']])}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="number">{{translate('Table Number')}} <span class="text-danger">*</span></label>
                                <input type="text" name="number" class="form-control" id="number"
                                       placeholder="{{translate('Ex')}} : {{translate('1')}}" value="{{$table->number}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">{{translate('Table Capacity')}} <span class="text-danger">*</span></label>
                                <input type="number" name="capacity" class="form-control" id="capacity"
                                       placeholder="{{translate('Ex')}} : {{translate('4')}}" min="1" max="99" value="{{$table->capacity}}" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">{{translate('submit')}}</button>
                </form>
            </div>

        </div>
    </div>

@endsection


