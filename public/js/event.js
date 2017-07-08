$(function () {
    $('#day').datepicker({
        language: $("meta[name=locale]").attr("content"),
        format: $("meta[name=date-format]").attr("content"),
        weekStart: $("meta[name=week-start]").attr("content"),
        orientation: "bottom auto",
        autoclose: true,
        todayHighlight: true
    });
});
