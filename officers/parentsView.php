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
                    echo '<h3>We cannot process your request please click <a href="parentsList.php">here</a> to go back to previous menu</h3>';
                }

            }
        ?>

        <?php
            // if no error load
            // print_r($validationMsg);
            if(empty($validationMsg['id'])) {
                $queryOne = query('SELECT * FROM parents WHERE id = :id', array('id'=>$id));

                // echo '<pre>';
                // print_r($queryOne);
                // if found data
                if($queryOne) {

        ?>
                    <h1><a href="parentsList.php">Parent List</a> / Parent ID <?= $queryOne[0]->id ?></h1>
                    <hr>

                    <div class="row">
                        <div class="column">
                            <p>
                                <label><b>Email:</b> </label>
                                <?= $queryOne[0]->email ?>
                            </p>
                            <p>
                                <label><b>Parent Name:</b> </label>
                                <?= $queryOne[0]->parent_name ?>
                            </p>
                            <p>
                                <label><b>Phone:</b> </label>
                                <?= $queryOne[0]->phone ?>
                            </p>
                        </div>
                        <div class="column">
                            <p>
                                <label><b>Address:</b> </label>
                                <?= $queryOne[0]->address ?>
                            </p>
                            <p>
                                <label><b>Postcode:</b> </label>
                                <?= $queryOne[0]->postcode ?>
                            </p>
                            <p>
                                <label><b>Status:</b> </label>
                                <?= ($queryOne[0]->active)? 'Active':'Not Active' ?>
                            </p>
                        </div>

                    </div>

                <?php
                    // calculate swimmers by email
                    $swimmersList = query('SELECT s.id, s.username, s.fname, s.lname, s.dob, s.active
                                       FROM swimmers s JOIN parents p ON s.email = p.email
                                       WHERE p.email = :email ORDER BY s.id',
                                          array('email'=>$queryOne[0]->email)
                                    );

                    // print_r($swimmersList);

                    // if swimmer already
                    if(count($swimmersList) > 0) {

                        if(count($swimmersList) < 6) {
                             echo '<a href="swimmersAdd.php"><button class="add">Add Swimmer</button></a><br><br>';
                        }

                ?>
                    <table class="gala-view table-striped">
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
                            // loop data
                            for($i=0; $i < count($swimmersList); $i++) {
                            echo '<tr>
                                    <td>'.$swimmersList[$i]->id.'</td>
                                    <td>'.$swimmersList[$i]->username.'</td>
                                    <td>'.$swimmersList[$i]->fname.'</td>
                                    <td>'.$swimmersList[$i]->lname.'</td>
                                    <td>'.$swimmersList[$i]->dob.'</td>';
                            echo    ($swimmersList[$i]->active) ? '<td>Active</td>':'<td>Not Active</td>';
                            echo    '<td>
                                        <a href="swimmersView.php?id='.$swimmersList[$i]->id.'"><button class="info">View</button></a>
                                    </td>
                                </tr>';
                            }

                         ?>
                    </table>

                <?php

                    } else {

                        echo '<a href="swimmersAdd.php"><button class="add">Add</button></a>';
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
                    echo '<h3>We cannot process your request please click <a href="parentsList.php">here</a> to go back to previous menu</h3>';

                }
            }
        ?>

    </main>

<?php

    include '../inc/loggedIn_footer.php';

 ?>