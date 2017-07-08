$(function() {
    $('.item-checkbox').on('change', function () { todoAjaxCheckItem(this); });
});

var todoAjaxCheckItem = function (obj) {
    var id = $(obj).data('id');
    var item_id = $(obj).val();
    var checked = $(obj).is(':checked') ? 1 : 0;

    if (checked) {
        $(obj).parent().parent().addClass('done');
    } else {
        $(obj).parent().parent().removeClass('done');
    }

    teamoShowLoading(trans.saving+'...');

    $.ajax({
        url: '/my/ajax_check_todo_item/'+id,
        type: 'POST',
        data: { item_id: item_id, checked: checked, _token: $('input[name="_token"]').val() },
        dataType: 'json',
        success: function(response) {
            if (response.status) {
                teamoHideLoading(trans.saved);
            }
        }
    });
};
