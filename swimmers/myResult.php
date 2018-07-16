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
    // include '../core/function/managementPageFunc.php';

    $validationMsg = array();
    $submittedData = array();

 ?>

    <main id="main-content">

    <?php
        $queryOne = query('SELECT s.id,s.username,s.fname,s.lname,s.dob, s.active,
                                          p.email, p.parent_name, p.phone, p.address, p.postcode
                                   FROM swimmers s JOIN parents p ON s.email = p.email
                                   WHERE s.id = :id',
                                   array('id'=>$userData[0]->id)
                                );

                // echo '<pre>';
                // print_r($queryOne);
                // if found data
                if($queryOne) {
     ?>
            <h1>My History</h1>
                    <hr>

                    <div class="row">
                        <div class="column">
                            <p>
                                <label><b>Username:</b> </label>
                                <?= $queryOne[0]->username ?>
                            </p>
                            <p>
                                <label><b>First Name:</b> </label>
                                <?= $queryOne[0]->fname ?>
                            </p>
                            <p>
                                <label><b>Last Name:</b> </label>
                                <?= $queryOne[0]->lname ?>
                            </p>
                            <p>
                                <label><b>Date of Birth:</b> </label>
                                <?= $queryOne[0]->dob ?>
                            </p>
                            <p>
                                <label><b>Email:</b> </label>
                                <?= $queryOne[0]->email ?>
                            </p>
                        </div>
                        <div class="column">
                            <p>
                                <label><b>Parent Name:</b> </label>
                                <?= $queryOne[0]->parent_name ?>
                            </p>
                            <p>
                                <label><b>Address:</b> </label>
                                <?= $queryOne[0]->address ?>
                            </p>
                            <p>
                                <label><b>Postcode:</b> </label>
                                <?= $queryOne[0]->postcode ?>
                            </p>
                            <p>
                                <label><b>Phone:</b> </label>
                                <?= $queryOne[0]->phone ?>
                            </p>
                            <p>
                                <label><b>Status:</b> </label>
                                <?= ($queryOne[0]->active)? 'Active':'Not Active' ?>
                            </p>
                        </div>
                    </div>

                <?php
                    // calculate HISTORY

                    $galaHistory = query("SELECT t.gala_id, g.date, t.line_number, t.recordtime, t.finish_number
                                          FROM timerecords t JOIN swimmers s ON t.swimmer_name = UPPER(CONCAT(s.lname, ', ', s.fname))
                                                             JOIN gala g ON g.id = t.gala_id
                                          WHERE t.swimmer_name = UPPER(CONCAT(:lname, ', ', :fname))
                                          ORDER BY g.date DESC",
                                          array('lname' => $queryOne[0]->lname, 'fname' => $queryOne[0]->fname)
                                      );

                    // echo '<pre>';
                    // print_r($galaHistory);

                    // if history found
                    if(count($galaHistory) > 0) {
                ?>

                <h2><?= $queryOne[0]->username  ?> History</h2>
                    <table class="gala-view table-striped">
                        <tr>
                            <th>Gala ID</th>
                            <th>Date</th>
                            <th>Line Number</th>
                            <th>Record Time</th>
                            <th>Finish Number</th>
                            <th>Action</th>
                        </tr>

                        <?php
                            // loop data
                            for($i=0; $i < count($galaHistory); $i++) {
                            echo '<tr>
                                    <td>'.$galaHistory[$i]->gala_id.'</td>
                                    <td>'.$galaHistory[$i]->date.'</td>
                                    <td>'.$galaHistory[$i]->line_number.'</td>
                                    <td>'.$galaHistory[$i]->recordtime.'</td>
                                    <td>'.$galaHistory[$i]->finish_number.'</td>
                                    <td>
                                        <a href="galaView.php?id='.$galaHistory[$i]->gala_id.'"><button class="info">View</button></a>
                                    </td>
                                </tr>';
                            }

                         ?>
                    </table>

     <?php
            // no result
            } else {
                echo '<h2>'.$queryOne[0]->username.' History</h2>';
                echo '<h4> No Results</h4>';
            }
        }
      ?>

    </main>

<?php

    include '../inc/loggedIn_footer.php';

 ?>