function searchOnKeyUp(id, field) {
    $("#" + id).on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("." + field).filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
}
