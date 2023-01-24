@extends('layouts.admin.app')

@section('title', translate('Promotional campaign'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="pb-3">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="">{{translate('Promotion Setup')}}</h1>

                    <div class="alert alert-soft-danger" role="alert">
                        <i class="tio-info"></i> {{translate('This_promotion_is_only_for_tab_view_of_table_app')}}
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.promotion.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlSelect1">{{translate('Select Banner Type')}} <span class="text-danger">*</span></label>
                                <select name="banner_type" id="banner_type" class="form-control js-select2-custom" required>
                                    <option value="" selected>{{ translate('--select--') }}</option>
                                    <option value="bottom_banner">{{ translate('Bottom Banner (1110*380 px)') }}</option>
                                    <option value="top_right_banner">{{ translate('Top Right Banner (280*450 px)') }}</option>
                                    <option value="bottom_right_banner">{{ translate('Bottom Right Banner (280*350 px)') }}</option>
                                    <option value="video">{{ translate('Video') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-12 from_part_2 video_section" id="video_section" style="display: none">
                                    <label class="input-label" for="exampleFormControlSelect1">{{translate('youtube Video URL')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="video" class="form-control" placeholder="{{ translate('ex : https://youtu.be/0sus46BflpU') }}">
                                </div>
                                <div class="col-12 from_part_2 image_section" id="image_section" style="display: none">
                                    <label class="input-label" for="exampleFormControlSelect1">{{translate('Image')}} <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" name="image" id="customFileEg" class="custom-file-input"
                                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                               oninvalid="document.getElementById('en-link').click()">
                                        <label class="custom-file-label" for="customFileEg">{{ translate('choose file') }}</label>
                                    </div>
                                    <div class="col-12 from_part_2 mt-2">
                                        <div class="form-group">
                                            <div class="text-center">
                                                <img style="height:170px;border: 1px solid; border-radius: 10px;" id="viewer"
                                                     src="{{ asset('public/assets/admin/img/400x400/img2.jpg') }}" alt="image" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">{{translate('Submit')}}</button>
                </form>
            </div>
        </div>


        <div class="card mt-2">
                <div class="card-header flex-between">
                    <div class="">
                        <h5>{{translate('Promotional campaign table')}}
                            <span style="color: red; padding: 0 .4375rem;">({{$promotions->total()}})</span>
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
                                <th>{{translate('Branch')}}</th>
                                <th>{{translate('Promotion type')}}</th>
                                <th>{{translate('Promotion Name')}}</th>
                                <th style="width: 50px">{{translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($promotions as $k=>$promotion)
                                <tr>
                                    <th scope="row" class="align-middle">{{$k+1}}</th>
                                    @if($promotion->branch )
                                    <td><a href="{{route('admin.promotion.branch',[$promotion->branch_id])}}">{{$promotion->branch->name }}</a></td>
                                    @else
                                        <td>{{ translate('Branch unavailable') }}</td>
                                    @endif
                                    <td>
                                        @php
                                            $promotion_type = $promotion['promotion_type'];
                                            echo str_replace('_', ' ', $promotion_type);
                                        @endphp
                                    </td>
                                    <td>
                                        @if($promotion['promotion_type'] == 'video')
                                            {{$promotion['promotion_name']}}
                                        @else
                                            <div style="height: 50px; width: 80px; overflow-x: hidden;overflow-y: hidden">
                                                <img src="{{asset('storage/app/public/promotion')}}/{{$promotion['promotion_name']}}" style="width: 100px">
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('admin.promotion.edit',[$promotion['id']])}}"
                                           class="btn btn-primary btn-sm"
                                           title="{{translate('Edit')}}">
                                            <i class="tio-edit"></i>
                                        </a>
                                        <a class="btn btn-danger btn-sm" title="{{translate('Delete')}}" href="javascript:"
                                           onclick="form_alert('promotion-{{$promotion['id']}}','{{translate('Want to delete this promotion ?')}}')">
                                            <i class="tio-delete"></i>
                                        </a>
                                        <form action="{{route('admin.promotion.delete',[$promotion['id']])}}"
                                              method="post" id="promotion-{{$promotion['id']}}">
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
                    {{$promotions->links()}}
                </div>
            </div>


    </div>
@endsection

@push('script_2')
    <script>
        $(function() {
            $('#banner_type').change(function(){
                if ($(this).val() === 'video'){
                    $('#video_section').show();
                    $('#image_section').hide();
                }else{
                    $('#video_section').hide();
                    $('#image_section').show();
                }
            });
        });

        function readURL(input, viewer_id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#'+viewer_id).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg").change(function () {
            readURL(this, 'viewer');
        });

    </script>
@endpush
