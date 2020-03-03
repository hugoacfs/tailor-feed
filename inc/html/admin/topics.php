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
                Topics
            </li>
        </ol>
    </nav>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text text-light bg-dark" id="basic-addon1">Search</span>
        </div>
        <input id="search-area-admin" type="text" class="form-control text-light bg-dark" placeholder="Example: chiuni" aria-label="Search" aria-describedby="basic-addon1">
    </div>
    <div class="table-responsive" style="max-height: 100%; max-height: 500px;">
        <table class="table table-dark tableFixHead table-striped " data-sortable>
            <thead>
                <tr>
                    <th scope="col">Reference Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Status</th>
                    <th scope="col">Update</th>
                    <th scope="col">Show/Hide</th>
                    <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $topics = $DB->fetchAllTopics();
                $count = 0;
                foreach ($topics as $topic) {
                    $count += 1;
                    $id = $topic['id'];
                    $name = $topic['name'];
                    $description = $topic['description'];
                    $status = $topic['status'];
                    if ($status === 'active') {
                        $quickAction = 'suspend-topic';
                        $btnStyle = 'class="btn btn-success mr-1"><i class="fas fa-eye"></i></button>';
                    } else {
                        $quickAction = 'activate-topic';
                        $btnStyle = 'class="btn btn-warning mr-1"><i class="fas fa-eye-slash"></i></button>';
                    }
                    $confirmMessage = "'Are you sure you want to permanently delete this topic from the database?'";
                    echo '<tr id="row-' . $id . '">';
                    echo '<td class="name">' . $name . '</td>';
                    echo '<td class="description">' . $description . '</td>';
                    echo '<td class="status">' . ucfirst($status) . '</td>';
                    echo '<td style="text-align:center;">';
                    echo '<button data-toggle="modal" value="' . $id . '" onClick="updateTopic(' . $id . ')" data-target="#modal" type="button" class="btn btn-primary mr-1"><i class="fas fa-edit"></i></button>';
                    echo '</td>';
                    echo '<td style="text-align:center;">';
                    echo '<form class="button-form" method="POST" action="admin.php?table=topics">';
                    echo '<div class="form-group hidden d-none"><input type="hidden" class="form-control" name="id" id="id" value="' . $id . '"></div>';
                    echo '<div class="form-group hidden d-none"><input type="hidden" class="form-control" name="action" id="action" value="' . $quickAction . '"></div>';
                    echo '<button value="' . $id . '" type="submit" ' . $btnStyle;
                    echo '</form>';
                    echo '</td>';
                    echo '<td style="text-align:center;">';
                    echo '<form class="button-form" method="POST" action="admin.php?table=topics">';
                    echo '<div class="form-group hidden d-none"><input type="hidden" class="form-control" name="id" id="id" value="' . $id . '"></div>';
                    echo '<div class="form-group hidden d-none"><input type="hidden" class="form-control" name="action" id="action" value="delete-topic"></div>';
                    echo '<button value="' . $id . '" type="submit" onclick="return confirm('.$confirmMessage.');" class="btn btn-danger mr-1"><i class="fas fa-trash"></i></button>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <hr>
    <div class="btn-group btn-group " role="group">
        <button data-toggle="modal" class="add-topic btn btn-success " data-target="#modal" onClick="addNewTopic()" type="button">
            <i class="fas fa-plus mr-1"></i>
            Add topic
        </button>
    </div>
</div>
<script>
    $('.add-source .btn .btn-success').click(function() {
        $('form[name="modalForm"]').submit();
    });
</script>