@extends('layouts.admin.app')

@section('title', translate('Chef Edit'))
    <style>
        .password-container{
            position: relative;
        }

        .togglePassword{
            position: absolute;
            top: 14px;
            right: 16px;
        }
    </style>
@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
    <div class="pb-3">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class=""><i class="tio-edit"></i> {{translate('Update Chef')}}</h1>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.kitchen.update',[$chef['id']])}}" method="post" enctype="multipart/form-data"
                          style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlSelect1">{{translate('Select Branch')}} <span class="text-danger">*</span></label>
                                    <select name="branch_id" class="form-control js-select2-custom" required>
                                        <option value="" selected>{{ translate('--select--') }}</option>
                                        @foreach($branches as $branch)
                                            <option value="{{$branch['id']}}" {{ $branch->id == $chef_branch->branch_id ? 'selected' : '' }}>{{$branch['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="f_name">{{translate('First Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="f_name" value="{{$chef['f_name']}}" class="form-control" id="f_name"
                                           placeholder="{{translate('First')}} : {{translate('Name')}}">
                                </div>
                                <div class="col-md-6">
                                    <label for="l_name">{{translate('Last Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="l_name" value="{{$chef['l_name']}}" class="form-control" id="l_name"
                                           placeholder="{{translate('Last')}} : {{translate('Name')}}">
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="name">{{translate('Phone')}}<span class="text-danger">*</span> {{translate('(with country code)')}}</label>
                                    <input type="text" value="{{$chef['phone']}}" required name="phone" class="form-control" id="phone"
                                           placeholder="{{translate('Phone')}}">
                                </div>
                                <div class="col-md-6">
                                    <label for="name">{{translate('Email')}} <span class="text-danger">*</span></label>
                                    <input type="email" value="{{$chef['email']}}" name="email" class="form-control" id="email"
                                           placeholder="{{translate('email')}}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="name">{{translate('Password')}}</label><small> ( {{translate('input if you want to change')}} )</small>
                                    <div class="password-container">
                                        <input type="password" name="password" class="form-control pr-7" id="password"
                                               placeholder="{{translate('Password')}}">
                                        <i class="tio-hidden-outlined togglePassword"></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">{{translate('image')}} <span class="text-danger">*</span> </label><span class="badge badge-soft-danger">( {{translate('ratio')}} 1:1 )</span>
                                        <div class="custom-file text-left">
                                            <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label" for="customFileUpload">{{translate('choose')}} {{translate('file')}}</label>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <img style="max-width: 100%;border: 1px solid; border-radius: 10px; max-height:200px;" id="viewer" src="{{asset('storage/app/public/kitchen')}}/{{$chef['image']}}" alt="image"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">{{translate('Update')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('script')
<!--    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>-->
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

    <script src="{{asset('public/assets/back-end')}}/js/select2.min.js"></script>

    <script>
        $('.togglePassword').on('click', function (e) {
            console.log("fired")
            const password = $(this).siblings('input');
            password.attr('type') === 'password' ? $(this).addClass('tio-visible-outlined').removeClass('tio-hidden-outlined') :$(this).addClass('tio-hidden-outlined').removeClass('tio-visible-outlined');
            const type = password.attr('type') === 'password' ? 'text' : 'password';
            password.attr('type', type);
        });

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


        // $(".js-example-theme-single").select2({
        //     theme: "classic"
        // });
        //
        // $(".js-example-responsive").select2({
        //     width: 'resolve'
        // });

        /*==================================
        togglePassword
       ====================================*/

    </script>

@endpush
