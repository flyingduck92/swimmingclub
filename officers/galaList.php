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
                    $validationMsg['form'] = successMsg('Gala Successfully Added');
                    output(@$validationMsg['form']);
            }
            if(isset($_GET['update_success']) && empty($_GET['update_success'])) {
                    $validationMsg['form'] = successMsg('Gala Successfully Updated');
                    output(@$validationMsg['form']);
            }
            if(isset($_GET['delete_success']) && empty($_GET['delete_success'])) {
                    $validationMsg['form'] = successMsg('Gala Successfully Deleted');
                    output(@$validationMsg['form']);
            }
         ?>

        <h1><a href="galaManagements.php">Gala Management</a> / Gala List</h1>
        <a href="galaAdd.php"><button class="add">Add</button></a>
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
            $results = getData('SELECT * FROM gala ORDER BY id', $limit, $page);

            // print_r($results);

         ?>

        <table class="gala table-striped">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Heat/Final</th>
                <th>Date</th>
                <th>Group</th>
                <th>Event</th>
                <th>Venue</th>
                <th>Note</th>
                <th>Action</th>
            </tr>
            <?php
                foreach ($results->data as $result) {
             ?>
                <tr>
                    <td><?= $result->id; ?></td>
                    <td><?= $result->category_name; ?></td>
                    <td><?= $result->heatfinal_desc; ?></td>
                    <td><?= $result->date; ?></td>
                    <td><?= $result->group_name; ?></td>
                    <td><?= $result->event_name; ?></td>
                    <td><?= $result->venue_name; ?></td>
                    <td><?= $result->note; ?></td>
                    <td>
                        <a href="galaView.php?id=<?= $result->id; ?>"><button class="info">View</button></a>
                        <a href="galaEdit.php?id=<?= $result->id; ?>"><button class="edit">Edit</button></a>
                        <a href="#<?= $result->id; ?>"><button class="delete">Delete</button></a>

                        <div class="modal" id="<?= $result->id; ?>">
                            <div class="modal-content">
                                <p>Delete Gala <b>ID <?= $result->id; ?></b></p><br>
                                <a href="#cancel"><button class="info">Cancel</button></a>
                                <a href="galaDelete.php?id=<?= $result->id; ?>"><button class="delete">Delete</button></a>
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