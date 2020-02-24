<?php
if (!defined('CONFIG_PROTECTION')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
// To add functionality
// if (isset($_POST['button-hide'])) {
//     $DB->updateTopicStatusById(intval($_POST['button-hide']));
//     // btnDelete
// } elseif (isset($_POST['button-show'])) {
//     // Assume btnSubmit
//     $DB->updateTopicStatusById(intval($_POST['button-show']));
// }
// if (isset($_POST['id'])) {
//     print_r($_POST);
//     $array = $_POST;
//     $DB->updateTopicById($array);
// }
unset($_POST);
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
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="admin.php">Admin</a>
            </li>
            <li class="breadcrumb-item active">
                Topics
            </li>
        </ol>
    </nav>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">Search</span>
        </div>
        <input id="search-area-admin" type="text" class="form-control" placeholder="Example: chiuni" aria-label="Search" aria-describedby="basic-addon1">
    </div>
    <div class="table-responsive" style="max-height: 100%; max-height: 500px;">
        <table class="table tableFixHead table-striped">
            <thead>
                <tr>
                    <th scope="col">Reference</th>
                    <th scope="col">Screen name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Update</th>
                    <th scope="col">Show/Hide</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $topics = $DB->fetchAllTopics();
                $count = 0;
                foreach ($topics as $topic) {
                    $count += 1;
                    $id = $topic['id'];
                    $reference = $topic['name'];
                    $screenname = $topic['description'];
                    $status = $topic['status'];
                    // $status = 'active';
                    echo '<tr>';
                    echo '<td style="display: none;">' . $id . '</td>';
                    echo '<td>' . $reference . '</td>';
                    echo '<td>' . $screenname . '</td>';
                    echo '<td>' . $status . '</td>';
                    echo '<td style="text-align:center;">';
                    echo '<button data-toggle="modal" data-target="#edit-modal" type="button" class="btn btn-primary mr-1"><i class="fas fa-edit"></i></button>';
                    echo '</td>';
                    echo '<td style="text-align:center;">';
                    echo '<form class="button-form" method="POST" action="admin.php">';
                    if ($status === 'active') {
                        echo '<button name="button-hide" value="' . $id . '" type="submit" class="btn btn-success mr-1"><i class="fas fa-eye"></i></button>';
                    } else {
                        echo '<button name="button-show" value="' . $id . '" type="submit" class="btn btn-danger mr-1"><i class="fas fa-eye-slash"></i></button>';
                    }
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>

            </tbody>
        </table>
    </div>
</div>