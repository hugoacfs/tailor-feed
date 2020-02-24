<?php
if (!defined('CONFIG_PROTECTION')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
?>
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="edit-modalLabel">Editing</h4>
                <button type="button" class="close" data-dismiss="modal"> <span aria-hidden="true" class="">× </span><span class="sr-only">Close</span>
                </button>

            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(".btn[data-target='#edit-modal']").click(function() {
        var columnHeadings = $("thead th").map(function() {
            return $(this).text();
        }).get();
        columnHeadings.pop();
        var columnValues = $(this).parent().siblings().map(function() {
            return $(this).text();
        }).get();
        var modalBody = $('<div id="modalContent"></div>');
        var modalForm = $('<form role="form" name="modalForm" action="admin.php" method="post"></form>');
        $.each(columnHeadings, function(i, columnHeader) {
            if (i == 0) {
                var formGroup = $('<div class="form-group"></div>');
                formGroup.append('<input type="hidden" class="form-control" name="id" id="id" value="' + columnValues[i] + '"/>');
                modalForm.append(formGroup);
            }
            if (i < 2) {
                var formGroup = $('<div class="form-group"></div>');
                formGroup.append('<label for="' + columnHeader + '">' + columnHeader + '</label>');
                formGroup.append('<input class="form-control" name="' + columnHeader + '" id="' + columnHeader + '" placeholder="' + columnValues[i + 1] + '" value="' + columnValues[i + 1] + '"/>');
                modalForm.append(formGroup);
            }
            if (i == 2) {
                var formGroup = $('<div class="form-group"></div>');
                formGroup.append('<label for="' + columnHeader + '">' + columnHeader + '</label>');
                formGroup.append('<select class="form-control" name="' + columnHeader + '" id="' + columnHeader + '" value="' + columnValues[i + 1] + '" ><option disabled selected>' + columnValues[i + 1] + '</option><option value="twitter">twitter</option><option value="facebook">facebook</option></select>');
                modalForm.append(formGroup);
            }
            if (i == 3) {
                var formGroup = $('<div class="form-group"></div>');
                formGroup.append('<label for="' + columnHeader + '">' + columnHeader + '</label>');
                formGroup.append('<select class="form-control" name="' + columnHeader + '" id="' + columnHeader + '" value="' + columnValues[i + 1] + '" ><option disabled selected>' + columnValues[i + 1] + '</option><option value="active">active</option><option value="suspended">suspended</option></select>');
                modalForm.append(formGroup);
            }
        });
        modalBody.append(modalForm);
        $('.modal-body').html(modalBody);
    });
    $('.modal-footer .btn-primary').click(function() {
        $('form[name="modalForm"]').submit();
    });
</script>