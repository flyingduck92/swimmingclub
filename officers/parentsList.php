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
            if(isset($_GET['success']) && empty($_GET['success'])) {
                    $validationMsg['form'] = successMsg('Parent Successfully Added');
                    output(@$validationMsg['form']);
            }
            if(isset($_GET['update_success']) && empty($_GET['update_success'])) {
                    $validationMsg['form'] = successMsg('Parent Successfully Updated');
                    output(@$validationMsg['form']);
            }
            if(isset($_GET['delete_success']) && empty($_GET['delete_success'])) {
                    $validationMsg['form'] = successMsg('Parent Successfully Deleted');
                    output(@$validationMsg['form']);
            }
        ?>

        <h1><a href="userManagement.php">User Management</a> / Parent List</h1>
        <a href="parentsAdd.php"><button class="add">Add Parent</button></a>
        <br>
        <br>

        <?php

            $page = (isset($_GET['page'])) ? (int)$_GET['page']:1;

            // if page less than 1
            if($page < 1) {
                header('Location: '.basename($_SERVER['PHP_SELF']));
            }

            // setup limit items per page
            $limit=10;
            $results = getData('SELECT * FROM parents ORDER BY parent_name', $limit, $page);

            // echo '<pre>';
            // print_r($results);
         ?>

        <table class="parent table-striped">
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Postcode</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php
                foreach ($results->data as $result) {
             ?>
                <tr>
                    <td><?= $result->id ?></td>
                    <td><?= $result->email ?></td>
                    <td><?= $result->parent_name ?></td>
                    <td><?= $result->phone ?></td>
                    <td><?= $result->address ?></td>
                    <td><?= $result->postcode ?></td>
                    <td><?= ($result->active)? 'Active':'Not Active' ?></td>
                    <td>
                    <a href="parentsView.php?id=<?= $result->id; ?>"><button class="info">View</button></a>
                    <a href="parentsEdit.php?id=<?= $result->id; ?>"><button class="edit">Edit</button></a>
                    <a href="#<?= $result->id; ?>"><button class="delete">Delete</button></a>

                    <div class="modal" id="<?= $result->id; ?>">
                        <div class="modal-content">
                            <p>Are you sure want to delete <b><?= $result->parent_name; ?></b> ?</p>
                            <p><b>Note: </b>This action will delete swimmers data that related to this user, but not affected to gala result</p><br>
                            <a href="#cancel"><button class="info">Cancel</button></a>
                            <a href="parentsDelete.php?id=<?= $result->id; ?>"><button class="delete">Delete</button></a>
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