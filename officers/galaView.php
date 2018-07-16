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
    include '../core/function/managementPageFunc.php';

    $expected = array('id');
    $validationMsg = array();
    $submittedData = array();

    // groupEdit.php?id=32
    if($_POST) {

        // print_r($_POST);

        // checking all required field
        foreach ($expected as $field) {
            $value = trim($_POST[$field]);

            if(isNotEmpty($value)) {
                ${$field} = htmlentities($value, ENT_COMPAT, 'UTF-8');
                // validate field type
                if($message = typePatternCheck($field, $value)) {
                    $validationMsg[$field] = errMsg($message);
                }
                $submittedData[$field] = $value;
            }
        }
    }

 ?>

    <main id="main-content">

        <?php
            // get data from page load where id
            if($_GET) {

                $id = trim((int)$_GET['id']);
                $id = htmlentities($id, ENT_COMPAT, 'UTF-8');

                if($message = typePatternCheck('id', $id)) {
                    $validationMsg['id'] = errMsg($message);
                }

                if($validationMsg) {
                    echo '<h1>Oops something happen!</h1>';
                    echo output(@$validationMsg['id']);
                    echo '<h3>We cannot process your request please click <a href="groupList.php">here</a> to go back to previous menu</h3>';
                }

            }
        ?>

        <?php
            // if no error load
            // print_r($validationMsg);
            if(empty($validationMsg['id'])) {
                $queryOne = query('SELECT * FROM gala WHERE id = :id', array('id'=>$id));

                // print_r($queryOne);
                // if found data
                if($queryOne) {

        ?>
                    <h1><a href="galaList.php">Gala List</a> / Gala ID <?= $queryOne[0]->id ?></h1>
                    <hr>

                    <?php

                        // show notification success add record
                        if(isset($_GET['success']) && empty($_GET['success'])) {
                            $validationMsg['form'] = successMsg('Time Record Successfully Added');
                            echo '<br>';
                            output(@$validationMsg['form']);
                        }
                        // show notification update record
                        if(isset($_GET['update_success']) && empty($_GET['update_success'])) {
                            $validationMsg['form'] = successMsg('Time Record Successfully Updated');
                            echo '<br>';
                            output(@$validationMsg['form']);
                        }
                        // show notification delete record
                        if(isset($_GET['delete_success']) && empty($_GET['delete_success'])) {
                            $validationMsg['form'] = successMsg('Time Record Successfully Deleted');
                            echo '<br>';
                            output(@$validationMsg['form']);
                        }

                     ?>

                    <div class="row">
                        <div class="column">
                            <p>
                                <label><b>Category:</b> </label>
                                <?= $queryOne[0]->category_name ?>
                            </p>
                            <p>
                                <label><b>Final/Heat:</b> </label>
                                <?= $queryOne[0]->heatfinal_desc ?>
                            </p>
                            <p>
                                <label><b>Date:</b> </label>
                                <?= $queryOne[0]->date ?>
                            </p>
                            <?php
                                // if note available
                                if(!empty($queryOne[0]->note)){
                                    echo '<p>
                                            <label><b>Note:</b> </label>
                                            '.$queryOne[0]->note.'
                                        </p>';
                                }
                             ?>
                        </div>
                        <div class="column">
                            <p>
                                <label><b>Group:</b> </label>
                                <?= $queryOne[0]->group_name ?>
                            </p>
                            <p>
                                <label><b>Event:</b> </label>
                                <?= $queryOne[0]->event_name ?>
                            </p>
                            <p>
                                <label><b>Venue:</b> </label>
                                <?= $queryOne[0]->venue_name ?>
                            </p>
                        </div>

                    </div>

                <?php
                    // calculate swimmers
                    $timerecords = query('SELECT id,gala_id,line_number, swimmer_name, recordtime, finish_number  FROM timerecords WHERE gala_id = :id ORDER BY line_number', array('id'=>$queryOne[0]->id));

                    // if swimmer already
                    if(count($timerecords) > 0) {

                        if(count($timerecords) < 5) {
                             echo '<a href="timerecordAdd.php?id='.$_GET['id'].'"><button class="add">Add</button></a><br>';
                        }
                ?>
                    <br>
                    <table class="gala-view table-striped">
                        <tr>
                            <th>Line Number</th>
                            <th>Swimmer Name</th>
                            <th>Record Time</th>
                            <th>Finish Number</th>
                            <th>Action</th>
                        </tr>

                        <?php
                            // loop data
                            for($i=0; $i < count($timerecords); $i++) {
                            echo '<tr>
                                    <td>'.$timerecords[$i]->line_number.'</td>
                                    <td>'.$timerecords[$i]->swimmer_name.'</td>
                                    <td>'.$timerecords[$i]->recordtime.'</td>
                                    <td>'.$timerecords[$i]->finish_number.'</td>
                                    <td>
                                        <a href="timerecordEdit.php?id='.$timerecords[$i]->id.'&gala_id='.$timerecords[$i]->gala_id.'"><button class="edit">Edit</button></a>
                                        <a href="#'.$timerecords[$i]->id.'"><button class="delete">Delete</button></a>

                                        <div class="modal" id="'.$timerecords[$i]->id.'">
                                            <div class="modal-content">
                                                <p>Delete <b>'.$timerecords[$i]->swimmer_name.'</b> ?</p><br>
                                                <a href="#cancel"><button class="info">Cancel</button></a>
                                                <a href="timerecordDelete.php?id='.$timerecords[$i]->id.'&gala_id='.$_GET['id'].'"><button class="delete">Delete</button></a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>';
                            }

                         ?>
                    </table>

                <?php

                    } else {

                        echo '<a href="timerecordAdd.php?id='.$_GET['id'].'"><button class="add">Add</button></a>';

                        echo '<div class="row">
                                <div class="column"><h3>No entries</h3></div>
                        </div>';
                    }


                 ?>

        <?php

                } else {

                    // if the id type is valid but doesn't exist
                    echo '<h1>Oops something happen!</h1>';
                    $validationMsg['id'] = errMsg('Unindentified id!');
                    echo output(@$validationMsg['id']);
                    echo '<h3>We cannot process your request please click <a href="galaList.php">here</a> to go back to previous menu</h3>';

                }
            }
        ?>

    </main>

<?php

    include '../inc/loggedIn_footer.php';

 ?>