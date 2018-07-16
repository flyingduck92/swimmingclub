<?php
    ob_start();
    include '../core/init.php';

    if (loggedIn() && $_SESSION['role_id'] == 1) {
        header('Location: ../officers/index.php');
        exit();

    } elseif (loggedIn() && $_SESSION['role_id'] == 2) {
        header('Location: ../parents/index.php');
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
                    $validationMsg['form'] = successMsg('Heat/Final Successfully Deleted');
                    output(@$validationMsg['form']);
            }
         ?>

        <h1> Gala Result</h1>

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
                <th>Details</th>
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