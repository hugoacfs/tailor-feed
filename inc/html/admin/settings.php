<?php
if (!defined('CONFIG_PROTECTION')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
?>
<script>
    $(document).ready(function() {
        $("#search-area-admin").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".table tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
<div class="col-xs-12 col-sm-12 col-lg-12">
    <nav>
        <ol class="breadcrumb text-light bg-dark">
            <li class="breadcrumb-item">
                <a href="admin.php">Admin</a>
            </li>
            <li class="breadcrumb-item active text-light">
                Settings
            </li>
        </ol>
    </nav>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text text-light bg-dark" id="basic-addon1">Search</span>
        </div>
        <input id="search-area-admin" type="text" class="form-control text-light bg-dark" placeholder="Example: chiuni" aria-label="Search" aria-describedby="basic-addon1">
    </div>
    <div class="table-responsive" style="max-height: 65vh;">
        <table class="table table-dark tableFixHead table-striped " data-sortable>
            <thead>
                <tr>
                    <th scope="col">Setting</th>
                    <th scope="col">Value</th>
                    <th scope="col">Update</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $settings = $DB->fetchConfiguration();
                $count = 0;
                foreach ($settings as $setting) {
                    $count += 1;
                    $id = $setting['id'];
                    $name = $setting['name'];
                    $name = explode("_", $name);
                    $name = implode(" ", $name);
                    $value = $setting['value'];
                    $confirmMessage = "'Are you sure you want to permanently delete this topic from the database?'";
                    echo '<tr id="row-' . $id . '">';
                    echo '<td class="name">' . ucfirst($name) . '</td>';
                    echo '<td class="value">' . $value . '</td>';
                    echo '<td style="text-align:center;">';
                    echo '<button data-toggle="modal" value="' . $id . '" data-target="#modal" onClick="updateSource(' . $id . ')" type="button" class="btn btn-primary mr-1"><i class="fas fa-edit"></i></button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <hr>
</div>
<script>
    $('.add-source .btn .btn-success').click(function() {
        $('form[name="modalForm"]').submit();
    });
</script>