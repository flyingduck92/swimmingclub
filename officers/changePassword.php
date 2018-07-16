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

    $expected = array('password');
    $validationMsg = array();
    $submittedData = array();

    if($_POST) {

        // checking all required field
        foreach ($expected as $field) {
            $value = trim($_POST[$field]);

            if(isNotEmpty($value)) {
                ${$field} = htmlentities($value, ENT_COMPAT, 'UTF-8');
                // check field length
                if($message = validateLength($field, $value)) {
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

            $options = [ 'cost' => 12 ];
            $password = password_hash($submittedData['password'], PASSWORD_BCRYPT, $options);

            // get parent key
            $updatePassword = query('UPDATE officers SET password = :password WHERE id = :id',
                              array(
                                'id' => $userData[0]->id,
                                'password' => $password
                            ));

            if($updatePassword) {
                header('location: changePassword.php?success');
            }
        }
    }

 ?>

    <main id="main-content">

        <h1><a href="index.php">Back</a> / Change Password </h1>
        <hr>

        <form method="post" action="changePassword.php">
            <?php
                // print_r($userData);
                // echo 'ID User '.$userData[0]->id;
             ?>
            <br>
            <label style="font-size: 14pt;"><b>Change Password</b> <br>
                <input required id="password" type="password" name="password" placeholder="Please input new password" value="<?php output(@$password) ?>">
                 <input type="checkbox" onclick="togglePassword('password')"> <small>Show Password</small>
            </label><br><br>
            <?php
                if(isset($_GET['success']) && empty($_GET['success'])) {
                    $validationMsg['form'] = successMsg('Password Successfully Updated');
                    output(@$validationMsg['form']);
                    echo '<br><br>';
                } else {
                    if($validationMsg) {
                        output(@$validationMsg['password']);
                        echo '<br><br>';
                    }
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