@extends('layouts.admin.app')

@section('title', translate('Employee Edit'))
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
    <link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <div class="pb-3">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="text-capitalize"><i
                        class="tio-edit"></i> {{translate('Employee Update')}}</h1>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">
                    <form action="{{route('admin.employee.update',[$e['id']])}}" method="post" enctype="multipart/form-data"
                          style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="name">{{translate('Name')}}</label>
                                    <input type="text" name="name" value="{{$e['f_name'] . ' ' . $e['l_name']}}" class="form-control" id="name"
                                           placeholder="{{translate('Name')}}">
                                </div>
                                <div class="col-md-6">
                                    <label for="name">{{translate('Phone')}}</label>
                                    <input type="text" value="{{$e['phone']}}" required name="phone" class="form-control" id="phone"
                                           placeholder="{{translate('Phone')}}">
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="name">{{translate('Email')}}</label>
                                    <input type="email" value="{{$e['email']}}" name="email" class="form-control" id="email"
                                           placeholder="{{translate('Email')}}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="name">{{translate('Role')}}</label>
                                    <select class="form-control" name="role_id"
                                            style="width: 100%" >
                                            <option value="0" selected disabled>---{{translate('select')}}---</option>
                                            @foreach($rls as $r)
                                                <option
                                                    value="{{$r->id}}" {{$r['id']==$e['admin_role_id']?'selected':''}}>{{translate($r->name)}}</option>
                                            @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="name">{{translate('Password')}}</label><small> ( {{translate('input if you want to change')}} )</small>
                                    <div class="password-container">
                                        <input type="password" name="password" class="form-control pr-7" id="password"
                                               placeholder="{{translate('Password')}}" required>
                                        <i  class="tio-hidden-outlined togglePassword"></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">{{translate('employee_image')}}</label><span class="badge badge-soft-danger">( {{translate('ratio')}} 1:1 )</span>
                                        <div class="custom-file text-left">
                                            <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label" for="customFileUpload">{{translate('choose')}} {{translate('file')}}</label>
                                        </div>
                                    </div>
<!--                                    <div class="text-center">
                                        <img style="width: auto;border: 1px solid; border-radius: 10px; max-height:200px;" id="viewer"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset('storage/app/public/admin')}}/{{$e['image']}}" alt="Employee thumbnail"/>
                                    </div>-->
                                    <div class="text-center">
                                        <img style="max-width: 100%;border: 1px solid; border-radius: 10px; max-height:200px;" id="viewer"
                                             src="{{asset('storage/app/public/admin')}}/{{$e['image']}}" alt="image"/>
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

    <!--modal-->
    @include('admin-views.employee.partials.image-process._image-crop-modal',['modal_id'=>'employee-image-modal'])
    <!--modal-->
</div>
@endsection

@push('script')
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


      /*  $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });*/


    </script>

    @include('admin-views.employee.partials.image-process._script',[
   'id'=>'employee-image-modal',
   'height'=>200,
   'width'=>200,
   'multi_image'=>false,
   'route'=>null
   ])
@endpush
