@extends('layouts.branch.app')

@section('title', translate('Promotional campaign'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-5 pb-5 mb-sm-0">
                    <h1 class="page-header-title">{{translate('Promotion Section')}}</h1>
                </div>
            </div>
        </div>

        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('branch.promotion.update',[$promotion['id']])}}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlSelect1">{{translate('Select Banner Type')}} <span class="text-danger">*</span></label>
                                <select name="banner_type" id="banner_type" class="form-control" required>
                                    <option value="" selected>{{ translate('--select--') }}</option>
                                    <option value="bottom_banner" {{ $promotion->promotion_type == 'bottom_banner' ? 'selected' : '' }}>{{ translate('Bottom Banner (1110*380 px)') }}</option>
                                    <option value="top_right_banner" {{ $promotion->promotion_type == 'top_right_banner' ? 'selected' : '' }}>{{ translate('Top Right Banner (280*450 px)') }}</option>
                                    <option value="bottom_right_banner" {{ $promotion->promotion_type == 'bottom_right_banner' ? 'selected' : '' }}>{{ translate('Bottom Right Banner (280*350 px)') }}</option>
                                    <option value="video" {{ $promotion->promotion_type == 'video' ? 'selected' : '' }}>{{ translate('Video') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                @if($promotion->promotion_type == 'video')
                                <div class="col-12 from_part_2 video_section" id="video_section">
                                    <label class="input-label" for="exampleFormControlSelect1">{{translate('youtube Video URL')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="video" value="{{$promotion->promotion_name}}" class="form-control" placeholder="{{ translate('ex : https://youtu.be/0sus46BflpU') }}">
                                </div>
                                @else
                                <div class="col-12 from_part_2 image_section" id="image_section">
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
                                                     src="{{asset('storage/app/public/promotion')}}/{{$promotion['promotion_name']}}" alt="image" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary mt-3">{{translate('Update')}}</button>
                </form>
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
