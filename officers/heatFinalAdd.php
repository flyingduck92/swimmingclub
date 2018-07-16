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

    $expected = array('heatfinal_desc');
    $validationMsg = array();
    $submittedData = array();

    if($_POST) {

        // checking all required field
        foreach ($expected as $field) {
            $value = trim($_POST[$field]);

            if(isNotEmpty($value)) {
                ${$field} = htmlentities($value, ENT_COMPAT, 'UTF-8');
                // validate field type & pattern
                if($message = typePatternCheck($field, $value)) {
                    $validationMsg[$field] = errMsg($message);
                }
                // check field length
                if($message = validateLength($field, $value)) {
                    $validationMsg[$field] = errMsg($message);
                }
                // check available or not
                if($message = checkAvailability($field, $value)) {
                    $validationMsg[$field] = errMsg($message);
                }
                $submittedData[$field] = $value;
            } else {
                if(isRequired($field)) {
                    $validationMsg[$field] = errMsg('*Required!');
                }
            }
        }

        // if no error
        if(empty($validationMsg)) {

            $heatfinalData = array('description' => $submittedData['heatfinal_desc']);

            // get parent key
            $heatfinalField   = implode(",", array_keys($heatfinalData));
            $heatfinalValue   = ":".implode(",:", array_keys($heatfinalData));

            $heatfinalInsert  = query('INSERT INTO heat_final('.$heatfinalField.') VALUES ('.$heatfinalValue.')', $heatfinalData);

            if($heatfinalInsert) {
                header('location: heatFinalAdd.php?success');
            }
        }
    }

 ?>

    <main id="main-content">

        <h1><a href="heatFinalList.php">Heat/Final List</a> / Heat/Final Add </h1>
        <hr>

        <form method="post" action="heatFinalAdd.php">
            <p><b>Note:</b> Only accept letters and numbers</p>

            <input required type="text" name="heatfinal_desc" placeholder="Please type here..." value="<?php output(@$heatfinal_desc) ?>"><br><br>
            <?php
                if(isset($_GET['success']) && empty($_GET['success'])) {
                        $validationMsg['form'] = successMsg('Heat/Final Successfully Added');
                        output(@$validationMsg['form']);
                }
                else {
                    output(@$validationMsg['heatfinal_desc']);
                }
            ?>
            <br><br>

            <a href="#test"><button type="button" class="info">Submit</button></a>

            <!-- Modal for confirmation -->
            <div class="modal" id="test">
                <div class="modal-content">
                    <p>Are you sure?</p><br>
                    <a href="#"><button type="button" class="edit">Cancel</button></a>
                    <a><button type="submit" class="info">Yes</button></a>
                </div>
            </div>
        </form>

    </main>

<?php

    include '../inc/loggedIn_footer.php';

 ?>