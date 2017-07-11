@extends('layouts.app')

@section('title', trans('profile.change_avatar'))

@section('header')
 {{ trans('profile.change_avatar') }}
@endsection

@section('scripts')
    <script type="text/javascript" src="/cropit/dist/jquery.cropit.js"></script>

    <script type="text/javascript">
        $(function () {
            $('#image-cropper').cropit({ imageState: {} });

            $('#save').click(function () {
                var datauri = $('#image-cropper').cropit('export', {type: 'image/jpeg', quality: 1, originalSize: true});
                $('#cropped-image').val(datauri);
            });
        });
    </script>
@append

@section('content')

    {!! Form::flash() !!}

    <section class="flying mb20">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">

                {!! Form::open() !!}

                <input type="hidden" id="cropped-image" name="datauri">

                <!-- This wraps the whole cropper -->
                <div id="image-cropper">
                    <!-- This is where the preview image is displayed -->
                    <div class="img-circle" style="position: absolute; width: 144px; height: 144px;"></div>
                    <div class="cropit-preview">
                        <div id="cropit-preview-label">{{ trans('profile.preview') }}</div>
                    </div>

                    <!-- This range input controls zoom -->
                    <!-- You can add additional elements here, e.g. the image icons -->
                    <input type="range" class="cropit-image-zoom-input mt20" />

                    <!-- This is where user selects new image -->
                    <div class="mt20 clearfix">
                        <div class="pull-left">
                            <span class="btn btn-default fileinput-button">
                                <span>{{ trans('app.select_file') }}</span>
                                <input type="file" class="cropit-image-input mt10">
                            </span>
                        </div>
                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary ml10" id="save">{{ trans('app.save') }}</button>
                            <a href="{{ route('user.profile.profile') }}" class="system ml10">{{ trans('app.cancel') }}</a>
                        </div>
                    </div>

                    <div class="mt20 c666 center">
                        {{ trans('profile.min_size') }} &#151; 300х300
                    </div>

                    <!-- The cropit- classes above are needed
                         so cropit can identify these elements -->
                </div>

                {!! Form::close() !!}

            </div>
            <div class="col-md-3">
            </div>
        </div>
    </section>
@endsection
