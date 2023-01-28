@extends('layouts.admin.app')

@section('title', translate('Add Membership Plan'))

@push('css_or_js')
    <link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{translate('Dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page">Membership Plan</li>
        </ol>
    </nav>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Add Membership From
                </div>
                <div class="card-body">
                    <form action="{{route('admin.membership.add-new')}}" method="post" enctype="multipart/form-data"
                          style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="name">Title</label>
                                    <input type="text" name="title" class="form-control" id="title"
                                           placeholder="title" value="{{old('name')}}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="name">Price</label>
                                    <input type="text" name="price" value="" class="form-control" id="price"
                                           placeholder="Price" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="name">Duration</label>
                                    <input type="text" name="duration" class="form-control" id="duration"
                                           placeholder="Duration" value="" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="name">Discount</label>
                                    <input type="text" name="discount" class="form-control" id="discount"
                                           placeholder="Discount" value="" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label for="name">Description</label>
                                    <textarea class="form-control" name="description">
                                        
                                    </textarea>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary float-right">{{translate('submit')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="{{asset('public/assets/admin')}}/js/select2.min.js"></script>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileUpload").change(function () {
            readURL(this);
        });

        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>
@endpush
