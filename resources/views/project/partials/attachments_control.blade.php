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
            @foreach (explode('|||', trim(old('files_list') , '|')) as $file)
                <?php list($f, $n) = explode(':::', $file); ?>
                <div class="uploaded-file" id="{{ $f }}" data-file="{{ $f }}" data-name="{{ $n }}">
                    {{ $n }}
                    <a href="javascript:void(0);" onclick="$('#{{ $f }}').hide();">
                        <i class="glyphicon glyphicon-remove c999"></i>
                    </a>
                </div>
            @endforeach
        @endif
    </div>

</div>
