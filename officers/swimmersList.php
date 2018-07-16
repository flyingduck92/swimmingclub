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
                    $validationMsg['form'] = successMsg('Swimmer Successfully Added');
                    output(@$validationMsg['form']);
            }
            if(isset($_GET['update_success']) && empty($_GET['update_success'])) {
                    $validationMsg['form'] = successMsg('Swimmer Successfully Updated');
                    output(@$validationMsg['form']);
            }
            if(isset($_GET['delete_success']) && empty($_GET['delete_success'])) {
                    $validationMsg['form'] = successMsg('Swimmer Successfully Deleted');
                    output(@$validationMsg['form']);
            }
        ?>

        <h1><a href="userManagement.php">User Management</a> / Swimmer List</h1>
        <a href="swimmersAdd.php"><button class="add">Add Swimmer</button></a>
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
            $results = getData('SELECT * FROM swimmers ORDER BY lname', $limit, $page);

            // echo '<pre>';
            // print_r($results);
         ?>

        <table class="parent table-striped">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Date of Birth</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php
                foreach ($results->data as $result) {
             ?>
                <tr>
                    <td><?= $result->id ?></td>
                    <td><?= $result->username ?></td>
                    <td><?= $result->fname ?></td>
                    <td><?= $result->lname ?></td>
                    <td><?= $result->dob ?></td>
                    <td><?= ($result->active)? 'Active':'Not Active' ?></td>
                    <td>
                    <a href="swimmersView.php?id=<?= $result->id; ?>"><button class="info">View</button></a>
                    <a href="swimmersEdit.php?id=<?= $result->id; ?>"><button class="edit">Edit</button></a>
                    <a href="#<?= $result->id; ?>"><button class="delete">Delete</button></a>

                    <div class="modal" id="<?= $result->id; ?>">
                        <div class="modal-content">
                            <p>Delete <b><?= $result->username; ?></b> ?</p><br>
                            <a href="#cancel"><button class="info">Cancel</button></a>
                            <a href="swimmersDelete.php?id=<?= $result->id; ?>"><button class="delete">Delete</button></a>
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