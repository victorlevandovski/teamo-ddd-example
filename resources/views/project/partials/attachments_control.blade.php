<div class="form-group">

    <input type="hidden" name="files_list" id="files-list" value="{{ old('files_list') }}">

    <div class="clearfix">
        <span class="fileinput-button system">
            <span><a href="javascript:void(0)">{{ trans('app.select_files') }}</a></span>

            <input id="fileupload" type="file" name="files[]" multiple>
        </span>
        <br>
        <div id="progress" style="float: right;">
            <div class="progress-bar progress-bar-success"></div>
        </div>
    </div>

    <div id="files" class="clearfix mt5" style="">
        @if (old('files_list'))
            <?php $files = json_decode(old('files_list'), true); ?>
            @foreach ($files as $file)
                <div class="uploaded-file" id="{{ $file['file'] }}" data-file="{{ $file['file'] }}" data-name="{{ $file['name'] }}">
                    {{ $file['name'] }}
                    <a href="javascript:void(0);" onclick="$('#{{ $file['file'] }}').hide();">
                        <i class="glyphicon glyphicon-remove c999"></i>
                    </a>
                </div>
            @endforeach
        @endif
    </div>

</div>
