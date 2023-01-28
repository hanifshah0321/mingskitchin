@extends('layouts.admin.app')

@section('title', translate('Membership Plan Edit'))

@push('css_or_js')
    <link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{translate('Dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page">Membership {{translate('Update')}} </li>
        </ol>
    </nav>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Membership Plan {{translate('Update')}} {{translate('form')}}
                </div>
                <div class="card-body">
                    <form action="{{route('admin.membership.update',[$e['id']])}}" method="post" enctype="multipart/form-data"
                          style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="title">Title</label>
                                    <input type="text" name="title" value="{{$e['title']}}" class="form-control" id="title"
                                           placeholder="Title">
                                </div>
                                <div class="col-md-6">
                                    <label for="name">Price</label>
                                    <input type="text" value="{{$e['price']}}" required name="price" class="form-control" id="price"
                                           placeholder="Price">
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="duration">Duration</label>
                                    <input type="text" value="{{$e['duration']}}" name="duration" class="form-control" id="duration"
                                           placeholder="Duration" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="name">Discount</label>
                                    <input type="text" name="discount" value="{{$e['discount']}}" class="form-control" id="discount"
                                           placeholder="Discount" value="" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="name">Description</label><small> 
                                    <textarea name="des" class="form-control">
                                        {{$e['des']}}
                                    </textarea>
                                </div>
                               
                            </div>
                        </div>


                        <button type="submit" class="btn btn-primary float-right">{{translate('Update')}}</button>

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


        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>

    @include('admin-views.employee.partials.image-process._script',[
   'id'=>'employee-image-modal',
   'height'=>200,
   'width'=>200,
   'multi_image'=>false,
   'route'=>null
   ])
@endpush
