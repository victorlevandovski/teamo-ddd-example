$(function () {
    'use strict';

    var url = '/my/ajax_file_upload';

    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            var file = '<div class="uploaded-file" id="' + data.result.file + '" '
                + 'data-file="' + data.result.file + '" '
                + 'data-name="' + data.result.name + '">'
                + data.result.name
                + ' <a href="javascript:void(0);" onclick="$(\'#' + data.result.file + '\').hide();"'
                + '><i class="glyphicon glyphicon-remove c999"></i></div>';
            $('#files').append(file);
            $('#progress').hide();
        },
        progressall: function (e, data) {
            $('#progress').show();
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }
    }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');

    $('#discussion-form,#discussion-comment-form').on('submit', function () {
        var files = '';

        $($('div[class="uploaded-file"]:visible').get()).each(function() {
            files += $(this).data('file') + ':::' + $(this).data('name') + '|||';
        });

        $('#files-list').val(files);
    });
});
