$(function () {
    var mass_toggle = true;

    $('.focus').focus();

    $('.mass-toggle').click(function () {
        if (mass_toggle) {
            $('.toggle').prop('checked', true);
            $('.mass-toggle').text($(this).data('select_none'));
        } else {
            $('.toggle').prop('checked', false);
            $('.mass-toggle').text($(this).data('select_all'));
        }

        mass_toggle = !mass_toggle;
    });

    $('.confirm').on('click', function () {
        return confirm($(this).data('confirm'));
    });

    $('.ajax-delete').on('click', function () {
        if (confirm($(this).data('confirm')))
        {
            var container = '#' + $(this).data('container');
            var deleted = $(this).data('deleted')

            $.ajax({
                url: $(this).data('href'),
                type: 'DELETE',
                data: { _token: $('input[name="_token"]').val() },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        if (deleted) {
                            $(container).html(deleted);
                        } else {
                            $(container).hide();
                        }
                    }
                }
            });
        }

        return false;
    });

    var scrollTo = $("meta[name=scroll-to]").attr("content");
    if (scrollTo.length) {
        location.hash = scrollTo;
    }

    $('#toggle-attachments-control').on('click', function () {
        $('#attachments-control').show();
        $(this).hide();
    });

});

function teamoShowLoading(message)
{
    $('#ajax-status').text(message).show();
}

function teamoHideLoading(message)
{
    $('#ajax-status').text(message).show();
    setTimeout(function () { $('#ajax-status').hide(); }, 1000);
}