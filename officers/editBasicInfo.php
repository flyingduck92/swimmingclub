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

    $expected = array('fname','lname','dob');
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

        // print_r($validationMsg);
        // print_r($submittedData);

        // if no error
        if(empty($validationMsg)) {

            // get parent key
            $updatePassword = query('UPDATE officers
                                     SET fname = :fname,
                                         lname = :lname,
                                         dob = :dob
                                     WHERE id = :id',
                              array(
                                'id' => $userData[0]->id,
                                'fname' => $submittedData['fname'],
                                'lname' => $submittedData['lname'],
                                'dob' => $submittedData['dob']
                            ));

            if($updatePassword) {
                header('location: editBasicInfo.php?success');
            }
        }
    }

 ?>

    <main id="main-content">

        <h1><a href="index.php">Back</a> / Edit Basic Information</h1>
        <hr>

        <form method="post" action="editBasicInfo.php">
            <?php
                // print_r($userData);
                // echo 'ID User '.$userData[0]->id;
             ?>
            <br>
            <label>
                First Name
                <input type="text" placeholder="Input your first name..." name="fname" required aria-required="true" maxlength="20" value="<?= (isset($userData[0]->fname)) ? $userData[0]->fname : output(@$fname) ?>">
                <?php output(@$validationMsg['fname']) ?>
            </label><br><br>
            <label>
                Last Name
                <input type="text" placeholder="Input your last name..." name="lname" required aria-required="true" maxlength="50" value="<?= (isset($userData[0]->lname)) ? $userData[0]->lname : output(@$lname) ?>"/>
                <?php output(@$validationMsg['lname']) ?>
            </label><br><br>
            <label>
                Date of Birth
                <input type="date" placeholder="Input your Date of Birth" name="dob" required aria-required="true" value="<?= (isset($userData[0]->dob)) ? $userData[0]->dob : output(@$dob) ?>" />
                <?php output(@$validationMsg['dob']) ?>
            </label><br><br>
            <?php
                if(isset($_GET['success']) && empty($_GET['success'])) {
                    $validationMsg['form'] = successMsg('Profile Successfully Updated');
                    output(@$validationMsg['form']);
                    echo '<br><br>';
                }
            ?>
            <a href="#confirmation"><button type="button" class="info">Submit</button></a>

            <!-- Modal for confirmation -->
            <div class="modal" id="confirmation">
                <div class="modal-content">
                    <p>Are you sure?</p><br>
                    <a href="#"><button type="button" class="edit">Cancel</button></a>
                    <a><button type="submit" class="info">Yes</button></a>
                </div>
            </div>
        </form>

    </main>

    <script>
        function togglePassword(id) {
            var id = document.getElementById(id);
            if(id.type === 'password') {
                id.type = 'text';
            } else {
                id.type = 'password';
            }
        }
    </script>

<?php

    include '../inc/loggedIn_footer.php';

 ?>