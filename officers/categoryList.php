<?php
    ob_start();
    include '../core/init.php';

    if (loggedIn() && $_SESSION['role_id'] == 2) {
        header('Location: ../parents/index.php');
        exit();

    } elseif (loggedIn() && $_SESSION['role_id'] == 3) {
        header('Location: ../swimmers/index.php');
        exit();

    } elseif (!loggedIn()) {
        header('Location: ../login.php');
        exit();
    }

    include '../inc/loggedIn_header.php';
    include '../inc/loggedIn_nav.php';
    include '../core/function/dataPaginate.php';

 ?>

    <main id="main-content">

        <?php
            if(isset($_GET['delete_success']) && empty($_GET['delete_success'])) {
                    $validationMsg['form'] = successMsg('Category Successfully Deleted');
                    output(@$validationMsg['form']);
            }
        ?>

        <h1><a href="galaManagements.php">Gala Management</a> / Category List</h1>
        <a href="categoryAdd.php"><button class="add">Add</button></a>
        <br>
        <br>

        <?php

            // data paginate
            $page = (isset($_GET['page'])) ? (int)$_GET['page']:1;

            // if page less than 1
            if($page < 1) {
                header('Location: '.basename($_SERVER['PHP_SELF']));
            }

            // setup limit items per page
            $limit=10;
            $results = getData('SELECT * FROM categories ORDER BY id', $limit, $page);

         ?>

        <table class="group">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
            <?php
                foreach ($results->data as $result) {
             ?>
                <tr>
                    <td><?= $result->id; ?></td>
                    <td><?= $result->name; ?></td>
                    <td>
                    <a href="categoryEdit.php?id=<?= $result->id; ?>"><button class="edit">Edit</button></a>
                    <a href="#<?= $result->id; ?>"><button class="delete">Delete</button></a>

                    <div class="modal" id="<?= $result->id; ?>">
                        <div class="modal-content">
                            <p>Delete <b><?= $result->name; ?></b> ?</p><br>
                            <a href="#cancel"><button class="info">Cancel</button></a>
                            <a href="categoryDelete.php?id=<?= $result->id; ?>"><button class="delete">Delete</button></a>
                        </div>
                    </div>
                </td>
                </tr>
            <?php
                }
             ?>
        </table>

        <?php
            echo '<br>';
            echo 'Total '.$results->total.'<br><br>';

            // pageLinks($links=3, $total, $limit, $page)
            // show links: show 1 prev link and 1 next link, from current page
            $links=1;
            echo $showLinks = pageLinks($links, $results->total, $results->limit, $results->page);
         ?>

    </main>

<?php

    include '../inc/loggedIn_footer.php';

 ?>