<?php
if (!defined('CONFIG_PROTECTION')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}

function buildGetString(array $params): string
{
    $queryString = '';
    foreach ($params as $key => $param) {
        $queryString .= '&amp;' . $key . '=' . $param;
    }
    return $queryString;
}
$max = $_GET['max'] ?? 5;
$page = $_GET['page'] ?? 1;
$s_id = $_GET['id'] ?? false;
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
<div class="col-12">
    <nav class="">
        <ol class="breadcrumb bg-dark text-light">
            <li class="breadcrumb-item ">
                <a href="admin.php">Admin</a>
            </li>
            <li class="breadcrumb-item active text-light">
                Articles
            </li>
            <div class="dropdown ml-auto ">
                <span>Number of Articles</span>
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo $max; ?>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="admin.php?table=articles&max=<?php echo 5; ?>&page=<?php echo $page; ?>">5</a>
                    <a class="dropdown-item" href="admin.php?table=articles&max=<?php echo 10; ?>&page=<?php echo $page; ?>">10</a>
                    <a class="dropdown-item" href="admin.php?table=articles&max=<?php echo -1; ?>&page=<?php echo $page; ?>">All</a>
                </div>
            </div>
        </ol>
    </nav>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text text-light bg-dark" id="basic-addon1">Search</span>
        </div>
        <input id="search-area-admin" type="text" class="form-control text-light bg-dark" placeholder="Example: chiuni" aria-label="Search" aria-describedby="basic-addon1">
    </div>
    <div class="table-responsive" style="max-height: 100%; max-height: 500px;">
        <table class="table tableFixHead table-striped sortable-theme-dark" data-sortable>
            <thead>
                <tr">
                    <th scope="col">Source Reference</th>
                    <th scope="col">Source Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Source Status</th>
                    <th scope="col">Body</th>
                    <th scope="col">Creation Date</th>
                    <th scope="col">Update</th>
                    <th scope="col">Delete</th>
                    </tr>
            </thead>
            <tbody>
                <?php
                $articles = $DB->fetchAllArticlesAndSources($page, $max, $s_id);
                $count = 0;
                foreach ($articles as $article) {
                    $count += 1;
                    $id = $article['id'];
                    $sourceid = $article['sourceid'];
                    $reference = $article['reference'];
                    $body = $article['body'];
                    $creationdate = $article['creationdate'];
                    $screenname = $article['screenname'];
                    $type = $article['type'];
                    $status = $article['status'];
                    if ($status === 'active') {
                        $quickAction = 'suspend-source';
                        $btnStyle = 'class="btn btn-success mr-1"><i class="fas fa-eye"></i></button>';
                    } else {
                        $quickAction = 'activate-source';
                        $btnStyle = 'class="btn btn-danger mr-1"><i class="fas fa-eye-slash"></i></button>';
                    }
                    echo '<tr id="row-' . $id . '">';
                    echo '<td class="reference"><a href="admin.php?table=articles&id=' . $sourceid . '">' . $reference . '</a></td>';
                    echo '<td class="screenname">' . $screenname . '</td>';
                    echo '<td class="type">' . ucfirst($type) . '</td>';
                    echo '<td class="type">' . ucfirst($status) . '</td>';
                    echo '<td class="status">' . $body . '</td>';
                    echo '<td class="status">' . $creationdate . '</td>';
                    echo '<td style="text-align:center;">';
                    echo '<button data-toggle="modal" value="' . $id . '" data-target="#modal" onClick="updateSource(' . $id . ')" type="button" class="btn btn-primary mr-1"><i class="fas fa-edit"></i></button>';
                    echo '</td>';
                    echo '<td style="text-align:center;">';
                    echo '<form class="button-form" method="POST" action="admin.php?table=sources">';
                    echo '<div class="form-group hidden d-none"><input type="hidden" class="form-control" name="id" id="id" value="' . $id . '"></div>';
                    echo '<div class="form-group hidden d-none"><input type="hidden" class="form-control" name="action" id="action" value="' . $quickAction . '"></div>';
                    echo '<button value="' . $id . '" type="submit" ' . $btnStyle;
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>

            </tbody>
        </table>
    </div>
    <hr>
    <div class="row">
        <div class="btn-group btn-group " role="group">
            <button data-toggle="modal" class="add-source btn btn-success " data-target="#modal" onClick="addNewSource()" type="button">
                <i class="fas fa-plus mr-1"></i>
                Add source
            </button>
        </div>
        <nav class="ml-auto mr-auto">
            <ul class="pagination">
                <li class="page-item <?php if ($page < 2) {
                                            echo 'disabled';
                                        } ?>">
                    <a class="page-link" href="admin.php?table=articles&max=<?php echo $max; ?>&page=<?php if ($page > 1) {
                                                                                                            echo ($page - 1);
                                                                                                        } ?>" tabindex="-1">Previous</a>
                </li>
                <li class="page-item active">
                    <a class="page-link" href="#"><?php echo ($page); ?> <span class="sr-only">(current)</span></a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="admin.php?table=articles&id=' . $sourceid . '&max=<?php echo $max; ?>&page=<?php echo ($page + 1); ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>
<script>
    $('.add-source .btn .btn-success').click(function() {
        $('form[name="modalForm"]').submit();
    });
</script>