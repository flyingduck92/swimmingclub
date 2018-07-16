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

    // ?id=32&gala_id
    if($_GET) {

        // checking all required field
        foreach ($expected as $field) {
            $value = trim($_GET[$field]);

            if(isNotEmpty($value)) {
                ${$field} = htmlentities($value, ENT_COMPAT, 'UTF-8');
                // validate field type
                if($message = typePatternCheck($field, $value)) {
                    $validationMsg[$field] = errMsg($message);
                }
                $submittedData[$field] = $value;
            }
        }

        // officer count check
        $countOfficer = query('SELECT * from officers');
        $officerCheck = query('SELECT username from officers WHERE id = '.(int)$submittedData['id']);

        // echo count($countOfficer);
        // echo '<pre>';
        // print_r($countOfficer);
        // print_r($officerCheck);

        // echo $username_from_session;

        if(count($countOfficer) > 1) {
            // if officer try to delete self
            if($username_from_session == $officerCheck[0]->username) {
                header('Location: officersList.php?self_delete');

            } else {
                $deleteOfficer = query('DELETE from officers WHERE id = :id', array('id' => $submittedData['id']));
                if($deleteOfficer) {
                    header('Location: officersList.php?delete_success');
                }
            }

        } else {
            // if officer only one
            header('Location: officersList.php?officer_one');
        }

    }
 ?>

 <main id="main-content">

        <?php
        if(!empty($validationMsg)) {
            echo '<h1>Oops something happen!</h1>';
            echo output(@$validationMsg['id']);
            echo '<h3>We cannot process your request please click <a href="officersList.php">here</a> to go back to previous menu</h3>';
        }
        ?>

    </main>

<?php

    include '../inc/loggedIn_footer.php';

 ?>