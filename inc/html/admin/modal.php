<?php
if (!defined('CONFIG_PROTECTION')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
?>
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-label"></h4>
                <button type="button" class="close" data-dismiss="modal"> <span aria-hidden="true" class="">× </span><span class="sr-only">Close</span>
                </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" id="cancel-btn" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="submit-btn" class="submit-btn btn btn-primary"></button>
            </div>
        </div>
    </div>
</div>
<script>
    function buildSelect(options, defaultValue, selectId) {
        // assume options = { value1 : 'Name 1', value2 : 'Name 2', ... }
        //        default = 'value1'
        var $select = $('<select class="form-control" name="' + selectId + '" id="' + selectId + '" value="' + defaultValue + '"></select>');
        var $option;
        for (var val in options) {
            $option = $('<option value="' + val + '">' + options[val] + '</option>');
            if (val == defaultValue) {
                $option.attr('selected', 'selected');
            }
            if (val == 'disabled') {
                $option.attr('selected', 'selected');
                $option.attr('disabled', 'disabled');
            }
            $select.append($option);
        }
        return $select;
    }

    // Modal Update Source Form

    function updateSource($id) {
        var modalBody = $('<div id="modalContent"></div>');
        var modalForm = $('<form role="form" name="modalForm" action="admin.php?table=sources" method="POST"></form>');
        var rowId = $id;

        var idValue = rowId;
        var idHtml = '<div class="form-group hidden"><input type="hidden" class="form-control" name="id" id="id" placeholder="' + idValue + '" value="' + idValue + '"></div>';

        var actionHtml = '<div class="form-group hidden"><input type="hidden" class="form-control" name="action" id="action" value="update-source"></div>';

        var referenceValue = $('#row-' + rowId + ' .reference').html();
        var referenceHtml = '<div class="form-group"><label for="reference">Reference</label><input class="form-control" name="reference" id="reference" placeholder="' + referenceValue + '" value="' + referenceValue + '" required /></div>';

        var screennameValue = $('#row-' + rowId + ' .screenname').html();
        var screennameHtml = '<div class="form-group"><label for="screenname">Name</label><input class="form-control" name="screenname" id="screenname" placeholder="' + screennameValue + '" value="' + screennameValue + '" required /></div>';

        var typeValue = $('#row-' + rowId + ' .type').html().toLowerCase();
        var typeOptions = {
            twitter: 'twitter',
            facebook: 'facebook',
            rss: 'rss'
        };
        var typeSelect = buildSelect(typeOptions, typeValue, 'type');
        var typeHtml = '<div class="form-group"><label for="type">Type</label>' + typeSelect.prop('outerHTML') + '</div>';

        var statusValue = $('#row-' + rowId + ' .status').html().toLowerCase();
        var statusOptions = {
            active: 'active',
            suspended: 'suspended'
        };
        var statusSelect = buildSelect(statusOptions, statusValue, 'status');
        var statusHtml = '<div class="form-group"><label for="status">Status</label>' + statusSelect.prop('outerHTML') + '</div>';

        modalForm.append(actionHtml);
        modalForm.append(idHtml);
        modalForm.append(referenceHtml);
        modalForm.append(screennameHtml);
        modalForm.append(typeHtml);
        modalForm.append(statusHtml);
        modalBody.append(modalForm);
        $('.modal-body').html(modalBody);
        $('#modal-label').html('Editing ' + referenceValue);
        $('#submit-btn').html('Update source');
    };
    // ADD SOURCE
    function addNewSource() {
        var modalBody = $('<div id="modalContent"></div>');
        var modalForm = $('<form role="form" name="modalForm" action="admin.php?table=sources" method="POST"></form>');

        var actionHtml = '<div class="form-group hidden"><input type="hidden" class="form-control" name="action" id="action" value="add-source"></div>';

        var referenceHtml = '<div class="form-group"><label for="reference">Reference</label><input class="form-control" name="reference" id="reference" placeholder="Reference Name" value="" required /></div>';

        var screennameHtml = '<div class="form-group"><label for="screenname">Name</label><input class="form-control" name="screenname" id="screenname" placeholder="Display Name" value="" required /></div>';

        var typeValue = 'default';
        var typeOptions = {
            disabled: 'Choose a type',
            twitter: 'twitter',
            facebook: 'facebook',
            rss: 'rss'
        };
        var typeSelect = buildSelect(typeOptions, typeValue, 'type');
        var typeHtml = '<div class="form-group"><label for="type">Type</label>' + typeSelect.prop('outerHTML') + '</div>';

        var statusValue = 'active';
        var statusOptions = {
            active: 'active',
            suspended: 'suspended'
        };
        var statusSelect = buildSelect(statusOptions, statusValue, 'status');
        var statusHtml = '<div class="form-group"><label for="status">Status</label>' + statusSelect.prop('outerHTML') + '</div>';

        modalForm.append(actionHtml);
        modalForm.append(referenceHtml);
        modalForm.append(screennameHtml);
        modalForm.append(typeHtml);
        modalForm.append(statusHtml);
        modalBody.append(modalForm);
        $('.modal-body').html(modalBody);
        $('#modal-label').html('Adding new source');
        $('#submit-btn').html('Add source');
    };
    // UPDATE TOPIC
    function updateTopic($id) {
        var modalBody = $('<div id="modalContent"></div>');
        var modalForm = $('<form role="form" name="modalForm" action="admin.php?table=topics" method="POST"></form>');
        var rowId = $id;

        var idValue = rowId;
        var idHtml = '<div class="form-group hidden"><input type="hidden" class="form-control" name="id" id="id" placeholder="' + idValue + '" value="' + idValue + '"></div>';

        var actionHtml = '<div class="form-group hidden"><input type="hidden" class="form-control" name="action" id="action" value="update-topic"></div>';

        var nameValue = $('#row-' + rowId + ' .name').html();
        var nameHtml = '<div class="form-group"><label for="name">Reference Name</label><input class="form-control" name="name" id="name" placeholder="#30daysofkindness" value="' + nameValue + '" required /></div>';

        var descriptionValue = $('#row-' + rowId + ' .description').html();
        var descriptionHtml = '<div class="form-group"><label for="description">Description</label><input class="form-control" name="description" id="description" placeholder="' + descriptionValue + '" value="' + descriptionValue + '" required /></div>';

        var statusValue = $('#row-' + rowId + ' .status').html().toLowerCase();
        var statusOptions = {
            active: 'active',
            suspended: 'suspended'
        };
        var statusSelect = buildSelect(statusOptions, statusValue, 'status');
        var statusHtml = '<div class="form-group"><label for="status">Status</label>' + statusSelect.prop('outerHTML') + '</div>';

        modalForm.append(actionHtml);
        modalForm.append(idHtml);
        modalForm.append(nameHtml);
        modalForm.append(descriptionHtml);
        modalForm.append(statusHtml);
        modalBody.append(modalForm);
        $('.modal-body').html(modalBody);
        $('#modal-label').html('Editing ' + nameValue);
        $('#submit-btn').html('Update topic');
    };
    // ADD TOPIC
    function addNewTopic() {
        var modalBody = $('<div id="modalContent"></div>');
        var modalForm = $('<form role="form" name="modalForm" action="admin.php?table=topics" method="POST"></form>');

        var actionHtml = '<div class="form-group hidden"><input type="hidden" class="form-control" name="action" id="action" value="add-topic"></div>';

        var nameHtml = '<div class="form-group"><label for="name">Name</label><input class="form-control" name="name" id="name" placeholder="#30daysofkindness" value="" required /></div>';

        var descriptionHTML = '<div class="form-group"><label for="description">Description</label><input class="form-control" name="description" id="description" placeholder="30 Days of Kindness March" value="" required /></div>';

        var statusValue = 'active';
        var statusOptions = {
            active: 'active',
            suspended: 'suspended'
        };
        var statusSelect = buildSelect(statusOptions, statusValue, 'status');
        var statusHtml = '<div class="form-group"><label for="status">Status</label>' + statusSelect.prop('outerHTML') + '</div>';

        modalForm.append(actionHtml);
        modalForm.append(nameHtml);
        modalForm.append(descriptionHTML);
        modalForm.append(statusHtml);
        modalBody.append(modalForm);
        $('.modal-body').html(modalBody);
        $('#modal-label').html('Adding new topic');
        $('#submit-btn').html('Add topic');
    };

    function updateSettings() {
        var modalBody = $('<div id="modalContent"></div>');
        var modalForm = $('<form role="form" name="modalForm" action="admin.php?table=topics" method="POST"></form>');

        var actionHtml = '<div class="form-group hidden"><input type="hidden" class="form-control" name="action" id="action" value="add-topic"></div>';

        var nameHtml = '<div class="form-group"><label for="name">Name</label><input class="form-control" name="name" id="name" placeholder="#30daysofkindness" value="" required /></div>';

        var descriptionHTML = '<div class="form-group"><label for="description">Description</label><input class="form-control" name="description" id="description" placeholder="30 Days of Kindness March" value="" required /></div>';

        var statusValue = 'active';
        var statusOptions = {
            active: 'active',
            suspended: 'suspended'
        };
        var statusSelect = buildSelect(statusOptions, statusValue, 'status');
        var statusHtml = '<div class="form-group"><label for="status">Status</label>' + statusSelect.prop('outerHTML') + '</div>';

        modalForm.append(actionHtml);
        modalForm.append(nameHtml);
        modalForm.append(descriptionHTML);
        modalForm.append(statusHtml);
        modalBody.append(modalForm);
        $('.modal-body').html(modalBody);
        $('#modal-label').html('Adding new topic');
        $('#submit-btn').html('Add topic');
    };

    $('#cancel-btn').click(function() {
        $('.modal-body').html('');
        $('#submit-btn').html('');
        $('#modal-label').html('');
    });

    $('#submit-btn').click(function() {
        $('form[name="modalForm"]').submit();
    });
</script>