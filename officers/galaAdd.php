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

    $expected = array('category_name','heatfinal_desc','date','group_name','event_name','venue_name','note');
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
                if($field == 'note') {}
                else if(isRequired($field)) {
                    $validationMsg[$field] = errMsg('*Required!');
                }
            }
        }

        // print_r($_POST);
        // print_r($submittedData);
        // print_r($validationMsg);

        if(empty($validationMsg)) {
            $galaData = $submittedData;
            $galaData['note'] = (isset($galaData['note'])) ? $galaData['note'] : '';

            // get parent key
            $galaField   = implode(",", array_keys($galaData));
            $galaValue   = ":".implode(",:", array_keys($galaData));

            $galaInsert  = query('INSERT INTO gala('.$galaField.') VALUES ('.$galaValue.')', $galaData);

            if($galaInsert) {
                header('location: galaList.php?success');
            }
        }

    }

 ?>

    <main id="main-content">

        <h1><a href="galaList.php">Back</a> / Add new gala</h1>
        <hr>

        <form method="post" action="galaAdd.php">
            <label>
                Gala Category:
                <input required list="category" type="text" name="category_name" value="<?= output(@$category_name) ?>">
                <?php output(@$validationMsg['category_name']); ?>
                <datalist id="category">
                <?php
                    // getAllSwimmer
                    $result = query("SELECT name from categories");

                    for($i = 0; $i<count($result);$i++) {
                        echo '<option value="'.$result[$i]->name.'">';
                    }

                 ?>
                </datalist>
            </label><br><br>
            <label>
                Heat/Final:
                <input required list="heatfinal" type="text" name="heatfinal_desc" value="<?php output(@$heatfinal_desc) ?>">
                <?php output(@$validationMsg['heatfinal_desc']); ?>

                <datalist id="heatfinal">
                <?php
                    // getAllSwimmer
                    $result = query("SELECT description from heat_final");

                    for($i = 0; $i<count($result);$i++) {
                        echo '<option value="'.$result[$i]->description.'">';
                    }

                 ?>
                </datalist>
            </label><br><br>
            <label>
                Date:
                <input required type="date" name="date" value="<?php output(@$date) ?>">
                <?php output(@$validationMsg['date']); ?>
            </label><br><br>
            <label>
                Group Name:
                <select name="group_name" value="<?php output(@$group_name) ?>">
                    <option value="" selected>Select group</option>
                <?php
                    // getAllSwimmer
                    $result = query("SELECT name from groups");
                    for($i = 0; $i<count($result);$i++) {
                        echo '<option value="'.$result[$i]->name.'">'.$result[$i]->name.'</option>';
                    }
                 ?>
                </select>
                <?php output(@$validationMsg['group_name']); ?>
            </label><br><br>
            <label>
                Event Name:
                <select name="event_name" value="<?php output(@$event_name) ?>">
                    <option value="" selected>Select Event</option>
                <?php
                    // getAllSwimmer
                    $result = query("SELECT event_name from events");
                    for($i = 0; $i<count($result);$i++) {
                        echo '<option value="'.$result[$i]->event_name.'">'.$result[$i]->event_name.'</option>';
                    }
                 ?>
                </select>
                <?php output(@$validationMsg['event_name']); ?>
            </label><br><br>
            <label>
                Venue Name:
                <select name="venue_name" value="<?php output(@$venue_name) ?>">
                    <option value="" selected>Select Venue</option>
                <?php
                    // getAllSwimmer
                    $result = query("SELECT name from venue");
                    for($i = 0; $i<count($result);$i++) {
                        echo '<option value="'.$result[$i]->name.'">'.$result[$i]->name.'</option>';
                    }
                 ?>
                </select>
                <?php output(@$validationMsg['venue_name']); ?>
            </label><br><br>
            <label>
                Note:
                <input type="text" name="note" value="<?php output(@$note) ?>">
                <?php output(@$validationMsg['note']); ?>
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

