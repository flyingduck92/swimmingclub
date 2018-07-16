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

    $expected = array('gala_id','line_number','swimmer_name','recordtime','finish_number');
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

        // print_r($submittedData);

        // if no error
        if(empty($validationMsg)) {

            $recordTimeData = $submittedData;

            // get parent key
            $recordTimeField   = implode(",", array_keys($recordTimeData));
            $recordTimeValue   = ":".implode(",:", array_keys($recordTimeData));

            $recordTimeInsert  = query('INSERT INTO timerecords('.$recordTimeField.') VALUES ('.$recordTimeValue.')', $recordTimeData);

            if($recordTimeInsert) {
                header('location: galaView.php?id='.$recordTimeData['gala_id'].'&success');
            }
        }
    }

 ?>

    <main id="main-content">

        <h1><a href="galaView.php?id=<?= $_GET['id']; ?>">Back</a> / Add timerecords</h1>
        <hr>

        <form method="post" action="timerecordAdd.php?id=<?= $_GET['id']; ?>">
            <p><b>Note:</b> Only accept letters and numbers</p>

            <label>
                Gala_id:
                <input style="width: 50px;" required type="text" name="gala_id" readonly value="<?= ($_GET['id']) ? htmlentities(trim($_GET['id'])):output(@$line_number) ?>">
                <?php output(@$validationMsg['id']) ?>
            </label><br><br>
            <label>
                Line Number:
                <input required type="number" name="line_number" min="1" max="5" placeholder="1-5" value="<?php output(@$line_number) ?>">
                <?php output(@$validationMsg['line_number']) ?>
            </label><br><br>
            <label>
                Swimmer Name:
                <input list='swimmername' required type="text" name="swimmer_name" value="<?php output(@$swimmer_name) ?>">
                <?php output(@$validationMsg['swimmer_name']) ?>
            </label>

            <datalist id="swimmername">
            <?php
                // getAllSwimmer
                $result = query("SELECT UPPER(CONCAT(lname,', ', fname)) as swimmer_name from swimmers");

                for($i = 0; $i<count($result);$i++) {
                    echo '<option value="'.$result[$i]->swimmer_name.'">';
                }

             ?>
            </datalist><br><br>
            <label for="time1">Time Record:
                <input style="width: 130px" required id="time1" type="text" name="recordtime" value="<?php output(@$recordtime) ?>">
                <?php output(@$validationMsg['recordtime']) ?>
            </label><br><br>
            <label>
                Finish Number:
                <input required type="number" name="finish_number" min="0" max="5" placeholder="0-5" value="<?php output(@$finish_number) ?>">
                <?php output(@$validationMsg['finish_number']) ?>
            </label><br><br>

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

