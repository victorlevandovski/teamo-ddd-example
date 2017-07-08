$(function() {

    $('#todos').sortable({
        axis: "y",
        handle: ".move-icon",
        containment: "#todos-section",
        update: function(event, ui) { todoAjaxSortItem(ui.item); }
    });

    $('.item-checkbox').on('change', function () { todoAjaxCheckItem(this); });

    $(document).click(function(e) {
        todoProcessDocumentClick();
    });

    $(document).keydown(function(e) {
        if (e.keyCode == 27) {
            $('div[data-id="new_item"]:visible').parent().remove();
            $('#todos').sortable('enable');
            $('#add-new-item-link').css('visibility', 'visible');
        }
        if (e.keyCode == 13) {
            if ($('#new-item-input:visible').val()) {
                todoSetNewItem($('#new-item-input:visible').val());
            }
        }
    });

    $('#add-new-item-link').click(function(e) {
        todoAddNewItemForm();
        e.stopPropagation();
    });

    $('#myModal').on('shown.bs.modal', function () {
        //$('#modal-name').focus();

        //
    });

    $('.modal-save').click(function (e) {
        $('#modal-name-ui').val($('#modal-assignee').find('option:selected').data('name'));
        $('#modal-assignee-avatar').val($('#modal-assignee').find('option:selected').data('avatar'));
        todoSaveItem();
    });

    $('#modal-assignee').change(function (e) {
        $('#modal-name-ui').val($(this).find('option:selected').data('name'));
    });

    $('#modal-deadline').datepicker({
        language: $("meta[name=locale]").attr("content"),
        format: $("meta[name=date-format]").attr("content"),
        weekStart: $("meta[name=week-start]").attr("content"),
        orientation: "bottom auto",
        autoclose: true,
        todayHighlight: true
    });

});

var todoContinueAdding = true;

var todoDeleteItem = function (obj) {
    if (confirm(trans.confirm_delete_item)) {
        todoAjaxDeleteItem($(obj).parent().parent().data('id'));
        $(obj).parent().parent().parent().remove();
    }
};

var todoEditItem = function (obj) {
    var container = $(obj).parent().parent();

    var name = $(container).find('.item-name').find('a').text();
    if (!name) {
        name = $(container).find('.item-name').text();
    }

    $('#modal-name').val(name);
    $('#modal-assignee').val($(container).find('.id-assignee-id').val());
    $('#modal-deadline').val($(container).find('.id-due-date').val());
    $('#modal-id').val($(container).data('id'));
    $('#myModal').modal();

    $('#modal-deadline').datepicker('setDate', $(container).find('.id-due-date').val());
};

var todoSaveItem = function () {
    var id = $('#modal-id').val();
    var container = $('div[data-id="'+id+'"]').parent();

    var name = $('#modal-name').val();
    var assignee_id = $('#modal-assignee').val();
    var assignee_avatar = $('#modal-assignee-avatar').val();
    var assignee_name = $('#modal-name-ui').val();
    var deadline = $('#modal-deadline').val();

    if (name) {
        $(container).find('.item-name').find('a').text(name);
        $(container).find('.item-details').empty().addClass('hide');
        $(container).find('.id-assignee-id').val(assignee_id);
        $(container).find('.id-due-date').val(deadline);

        if (assignee_name) {
            $(container).find('.item-details').append('<img src="'+assignee_avatar+'" class="avatar24 todo-item-avatar"> ');
            $(container).find('.item-details').append(document.createTextNode(assignee_name));
            if (deadline) {
                $(container).find('.item-details').append(' ');
            }
            $(container).find('.item-details').removeClass('hide');
        }
        if (deadline) {
            var cal = $('<div class="todo-deadline-icon todo-icon"><div class="day"></div></div>');
            $(cal).attr('title', trans.due+' ' + humanizeDate(deadline));
            $(cal).find('div').text(getDateDay(deadline));
            if (assignee_name) {
                $(cal).addClass('ml10');
            }
            $(container).find('.item-details').append(cal);
            $(container).find('.item-details').removeClass('hide');
        }

        todoAjaxEditItem(id, name, assignee_id, deadline);
    }
};

var todoAjaxEditItem = function (item_id, item_name, item_assignee_id, item_due_date) {
    var id = $('input[name="id"]').val();
    var selector = 'div[data-id="' + item_id + '"]';

    teamoShowLoading(trans.saving+'...');

    $.ajax({
        url: '/my/ajax_edit_todo_item/'+id,
        type: 'POST',
        data: {
            id: item_id,
            name: item_name,
            assignee_id: item_assignee_id,
            deadline: item_due_date,
            _token: $('input[name="_token"]').val()
        },
        dataType: 'json',
        success: function(response) {
            if (response.status) {
                $(selector).find('.item-name').html(response.name);
                teamoHideLoading(trans.saved);
            }
        }
    });
};

var todoAjaxCheckItem = function (obj) {
    var id = $('input[name="id"]').val();
    var item_id = $(obj).parent().parent().data('id');
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

var todoAjaxSortItem = function (obj) {
    var id = $('input[name="id"]').val();
    var item_id = $(obj).find('div').data('id');

    var selector = 'div[data-id="' + item_id + '"]';
    var order = $('div.item-div').index($(selector)) + 1;

    teamoShowLoading(trans.saving+'...');

    $.ajax({
        url: '/my/ajax_sort_todo_item/'+id,
        type: 'POST',
        data: { item_id: item_id, order: order, _token: $('input[name="_token"]').val() },
        dataType: 'json',
        success: function(response) {
            if (response.status) {
                teamoHideLoading(trans.saved);
            }
        }
    });
};

var todoAjaxDeleteItem = function (item_id) {
    var id = $('input[name="id"]').val();

    teamoShowLoading(trans.saving+'...');

    $.ajax({
        url: '/my/ajax_delete_todo_item/'+id,
        type: 'POST',
        data: { item_id: item_id, _token: $('input[name="_token"]').val() },
        dataType: 'json',
        success: function(response) {
            if (response.status) {
                teamoHideLoading(trans.saved);
            }
        }
    });
};

var todoAjaxAddItem = function (item_id, item_name, item_assignee_id, item_due_date) {
    var id = $('input[name="id"]').val();

    var selector = 'div[data-id="' + item_id + '"]';

    teamoShowLoading(trans.saving+'...');

    $.ajax({
        url: '/my/ajax_add_todo_item/'+id,
        type: 'POST',
        data: {
            name: item_name,
            assignee_id: item_assignee_id,
            deadline: item_due_date,
            _token: $('input[name="_token"]').val()
        },
        dataType: 'json',
        success: function(response) {
            if (response.status && response.id) {
                $(selector).attr('data-id', response.id).find('.item-name').html(response.name);
            }
            teamoHideLoading(trans.saved);
        }
    });

    if (todoContinueAdding) {
        todoAddNewItemForm();
    } else {
        $('#todos').sortable('enable');
        $('#add-new-item-link').css('visibility', 'visible');
    }
};

var todoAddNewItemForm = function () {
    if ($('div[data-id="new_item"]:visible').length) {
        return;
    }

    $('#add-new-item-link').css('visibility', 'hidden');

    $('#todos').sortable('disable');
    todoContinueAdding = true;

    var li = $('#new-item-li').find('li').clone();
    $('#todos').append(li);
    $('#new-item-input:visible').focus();

    $('div[data-id="new_item"]:visible').parent().on('click', '*', function(e) {
        e.stopPropagation();
    });

    $('#assign-to-link:visible').click(function (e) {
        var container = $('div[data-id="new_item"]:visible').parent();
        if ($(container).find('#assign-to:visible').length) {
            $( "#assign-to" ).css('display', 'none');
            if ($(container).find('.id-assignee-id').val() == 0) {
                $(this).removeClass('additional-selected').addClass('system');
            }
            if ($('#new-item-input:visible').val() == '') {
                $('#new-item-input:visible').focus();
            }
        } else {
            $(this).removeClass('system').addClass('additional-selected');
            $( "#assign-to" ).css('display', 'inline-block');
        }
    });

    $('#due-date-link').datepicker({
        language: $("meta[name=locale]").attr("content"),
        format: $("meta[name=date-format]").attr("content"),
        weekStart: $("meta[name=week-start]").attr("content"),
        orientation: "bottom auto",
        autoclose: true,
        todayHighlight: true
    });

    $('#due-date-link').on("changeDate", function() {
        var container = $('div[data-id="new_item"]:visible');
        var date = $('#due-date-link').datepicker('getFormattedDate');
        $(container).find('.id-due-date').val(date);
        $(container).find('.id-due-date-text').val(trans.due + ' ' + humanizeDate(date));
        $(this).removeClass('system').addClass('additional-selected').text(trans.due + ' ' + date);
        if ($('#new-item-input:visible').val() == '') {
            $('#new-item-input:visible').focus();
        }
    });

    $('#save-item:visible').click(function (e) {
        e.stopPropagation();
        if ($('#new-item-input:visible').val()) {
            todoSetNewItem($('#new-item-input:visible').val());
        } else {
            $('#new-item-input:visible').focus();
        }
    });
};

var todoSetNewItem = function (name) {
    var container = $('div[data-id="new_item"]:visible');
    var id = Math.round(Math.random() * 999999999);

    var assignee_id = $(container).find('.id-assignee-id').val();
    var due_date = $(container).find('.id-due-date').val();

    var assignee_text = $(container).find('.id-assignee-text').val();
    var due_date_text = $(container).find('.id-due-date-text').val();
    var assignee_avatar = $(container).find('.id-assignee-avatar').val();

    $(container).find('#new-item-input').remove();
    $(container).parent().find('.additionals-panel').remove();
    $(container).find('div.item-name').text(name);
    $(container).find('div.move-icon').removeClass('hide-icon');
    $(container).find('div.item-controls').removeClass('hide');
    $(container).attr('data-id', id);
    $(container).find('.item-checkbox').on('change', function () { todoAjaxCheckItem(this); });

//    if (assignee_text) {
//        $(container).find('.item-details').append(assignee_text);
//        if (due_date_text) {
//            $(container).find('.item-details').append(' ');
//        }
//        $(container).find('.item-details').removeClass('hide');
//    }
//    if (due_date_text) {
//        $(container).find('.item-details').append(due_date_text);
//        $(container).find('.item-details').removeClass('hide');
//    }

    if (assignee_text) {
        $(container).find('.item-details').append('<img src="'+assignee_avatar+'" class="avatar24 todo-item-avatar"> ');
        $(container).find('.item-details').append(document.createTextNode(assignee_text));
        if (due_date_text) {
            $(container).find('.item-details').append(' ');
        }
        $(container).find('.item-details').removeClass('hide');
    }
    if (due_date_text) {
        var cal = $('<div class="todo-deadline-icon todo-icon"><div class="day"></div></div>');
        $(cal).attr('title', due_date_text);
        $(cal).find('div').text(getDateDay(due_date));
        if (assignee_text) {
            $(cal).addClass('ml10');
        }
        $(container).find('.item-details').append(cal);
        $(container).find('.item-details').removeClass('hide');
    }

    todoAjaxAddItem(id, name, assignee_id, due_date);
};

var todoSetAssignee = function (obj, id) {
    var container = $('div[data-id="new_item"]:visible').parent();

    $(container).find('.id-assignee-id').val(id);
    $(container).find('.id-assignee-text').val($(obj).text());
    $(container).find('.id-assignee-avatar').val($(obj).data('avatar'));

    $(container).find('#assign-to').hide();
    $(container).find('#assign-to-link').text($(obj).data('user'));

    if ($('#new-item-input:visible').val() == '') {
        $('#new-item-input:visible').focus();
    }
};

var todoProcessInput = function (input, event) {
    if (event.keyCode == 13) {
        if ($(input).val()) {
            todoSetNewItem($(input).val());
        } else {
            $('div[data-id="new_item"]:visible').parent().remove();
            $('#todos').sortable('enable');
            $('#add-new-item-link').css('visibility', 'visible');
        }
    }
};

var todoProcessDocumentClick = function () {
    todoContinueAdding = false;

    if ($('div[data-id="new_item"]:visible').length) {
        if ($('#new-item-input:visible').val()) {
            todoSetNewItem($('#new-item-input:visible').val());
        } else {
            $('div[data-id="new_item"]:visible').parent().remove();
            $('#todos').sortable('enable');
            $('#add-new-item-link').css('visibility', 'visible');
        }
    }
};

function escapeHtml(text) {
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };

    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

function humanizeDate(date)
{
    var format = $("meta[name=date-format]").attr("content");

    var tmp = '';
    var day = 0;
    var month = 0;

    if (format == 'mm/dd/yyyy') {
        tmp = date.split('/');
        day = parseInt(tmp[1]);
        month = parseInt(tmp[0]) - 1;
    } else {
        tmp = date.split('.');
        day = parseInt(tmp[0]);
        month = parseInt(tmp[1]) - 1;
    }

    var locale = $("meta[name=locale]").attr("content");
    if (locale == 'ru') {
        return day + ' ' + trans.months[month];
    } else {
        return trans.months[month] + ' ' + day;
    }

}

function getDateDay(date)
{
    var format = $("meta[name=date-format]").attr("content");
    var tmp = '';

    if (format == 'mm/dd/yyyy') {
        tmp = date.split('/');
        return parseInt(tmp[1]);
    } else {
        tmp = date.split('.');
        return parseInt(tmp[0]);
    }
}
