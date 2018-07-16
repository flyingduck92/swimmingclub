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

    $expected = array('event_name');
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

            $categoryData = array('event_name' => $submittedData['event_name']);

            // get parent key
            $categoryField   = implode(",", array_keys($categoryData));
            $categoryValue   = ":".implode(",:", array_keys($categoryData));

            $categoryInsert  = query('INSERT INTO events('.$categoryField.') VALUES ('.$categoryValue.')', $categoryData);

            if($categoryInsert) {
                header('location: eventsAdd.php?success');
            }
        }
    }

 ?>

    <main id="main-content">

        <h1><a href="eventsList.php">Events List</a> / Event Add </h1>
        <hr>

        <form method="post" action="eventsAdd.php">
            <p><b>Note:</b> Only accept letters and numbers</p>

            <input required type="text" name="event_name" placeholder="Please input new event" value="<?php output(@$event_name) ?>"><br><br>
            <?php
                if(isset($_GET['success']) && empty($_GET['success'])) {
                        $validationMsg['form'] = successMsg('Group Successfully added');
                        output(@$validationMsg['form']);
                }
                else {
                    output(@$validationMsg['event_name']);
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