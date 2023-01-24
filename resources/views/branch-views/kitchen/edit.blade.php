@extends('layouts.branch.app')

@section('title', translate('Chef Edit'))

@push('css_or_js')
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
                    <form action="{{route('branch.kitchen.update',[$chef['id']])}}" method="post" enctype="multipart/form-data"
                          style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        @csrf

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="f_name">{{translate('First Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="f_name" value="{{$chef['f_name']}}" class="form-control" id="f_name"
                                           placeholder="{{translate('Ex')}} : {{translate('John')}}">
                                </div>
                                <div class="col-md-6">
                                    <label for="l_name">{{translate('Last Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="l_name" value="{{$chef['l_name']}}" class="form-control" id="l_name"
                                           placeholder="{{translate('Ex')}} : {{translate('Doe')}}">
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="name">{{translate('Phone')}} <span class="text-danger">*</span> {{translate('(with country code)')}}</label>
                                    <input type="text" value="{{$chef['phone']}}" required name="phone" class="form-control" id="phone"
                                           placeholder="{{translate('Ex')}} : +88017********">
                                </div>
                                <div class="col-md-6">
                                    <label for="name">{{translate('Email')}} <span class="text-danger">*</span></label>
                                    <input type="email" value="{{$chef['email']}}" name="email" class="form-control" id="email"
                                           placeholder="{{translate('Ex')}} : ex@gmail.com" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="name">{{translate('Password')}}</label><small> ( {{translate('input if you want to change')}} )</small>
                                    <div class="password-container">
                                        <input type="password" name="password" class="form-control pr-7" id="password"
                                               placeholder="{{translate('Password')}}" >
                                        <i class="tio-hidden-outlined togglePassword"></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">{{translate('image')}}</label> <span class="text-danger">*</span> <span class="badge badge-soft-danger">( {{translate('ratio')}} 1:1 )</span>
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
    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="{{asset('public/assets/back-end')}}/js/select2.min.js"></script>
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


      /*  $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });*/

        /*==================================
      togglePassword
     ====================================*/
        $('.togglePassword').on('click', function (e) {
            console.log("fired")
            const password = $(this).siblings('input');
            password.attr('type') === 'password' ? $(this).addClass('tio-visible-outlined').removeClass('tio-hidden-outlined') :$(this).addClass('tio-hidden-outlined').removeClass('tio-visible-outlined');
            const type = password.attr('type') === 'password' ? 'text' : 'password';
            password.attr('type', type);
        });
    </script>

@endpush
