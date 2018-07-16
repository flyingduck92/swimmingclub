<?php
    ob_start();
    include '../core/init.php';

    if (loggedIn() && $_SESSION['role_id'] == 1) {
        header('Location: ../officers/index.php');
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

    $expected = array('id','password','fname','lname','dob');
    $validationMsg = array();
    $submittedData = array();

    // groupEdit.php?id=32
    if($_POST) {

        $_POST['active'] = (isset($_POST['active']) && ($_POST['active'] == 'on')) ? 1 : 0;

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

            } else {
                if($field == 'email' || $field == 'phone' || $field == 'password' ) {}
                else if(isRequired($field)) {
                    $validationMsg[$field] = errMsg('*Required!');
                }
            }
        }

        // print_r($validationMsg);
        // print_r($_POST);

        if(empty($validationMsg)) {

            // if empty password
            if(empty($_POST['password'])) {
                $submittedData = array(
                    'id' => $_POST['id'],
                    'fname' => $_POST['fname'],
                    'lname' => $_POST['lname'],
                    'dob' => $_POST['dob']
                );

                $updateSwimmer = query('UPDATE swimmers
                                      SET fname = :fname,
                                          lname = :lname,
                                          dob = :dob
                                      WHERE id = :id', $submittedData);

            // if password not empty
            } else {

                // encrypt password
                $options = [ 'cost' => 12 ];
                $_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);

                 $submittedData = array(
                    'id' => $_POST['id'],
                    'password' => $_POST['password'],
                    'fname' => $_POST['fname'],
                    'lname' => $_POST['lname'],
                    'dob' => $_POST['dob']
                );

                $updateSwimmer = query('UPDATE swimmers
                                      SET password = :password,
                                          fname = :fname,
                                          lname = :lname,
                                          dob = :dob
                                      WHERE id = :id', $submittedData);

            }

            if($updateSwimmer) {
                header('Location: swimmersList.php?update_success');
            }

        }

    }

 ?>

    <main id="main-content">

        <?php
            // get data from page load by id
            if($_GET) {

                $id = trim($_GET['id']);
                $id = htmlentities($id, ENT_COMPAT, 'UTF-8');

                if($message = typePatternCheck('id', (int)$id)) {
                    $validationMsg['id'] = errMsg($message);
                }

                if(isset($validationMsg['id'])) {
                    echo '<h1>Oops something happen!</h1>';
                    echo output(@$validationMsg['id']);
                    echo '<h3>We cannot process your request please click <a href="parentsList.php">here</a> to go back to previous menu</h3>';
                }

            }
        ?>

        <?php
            // if no error load
            // print_r($validationMsg);
            if(empty($validationMsg['id']) && isset($_GET['id'])) {
                $queryOne = query('SELECT * FROM swimmers WHERE id = :id', array('id'=>$id));

                // if found data
                if($queryOne) {
                    // echo '***** <br>';
                    // print_r($queryOne);

        ?>
                    <h1><a href="swimmersList.php">Back</a> / Edit Swimmer</h1>
                    <hr>

                    <form method="post" action="swimmersEdit.php?id=<?= $queryOne[0]->id ?>">
                        <label>
                            Swimmer ID
                            <input readonly required type="number" name="id" value="<?= (isset($queryOne[0]->id)) ? $queryOne[0]->id : output(@$id) ?>">
                            <?php output(@$validationMsg['id']) ?>
                        </label><br><br>
                        <label>
                            Username <b><?= $queryOne[0]->username; ?></b>
                        </label><br><br>
                        <label>
                            Password
                            <input aria-required="true" maxlength="25" id="password" type="password" name="password" placeholder="Please type password..." value="<?php output(@$password) ?>">
                            <input type="checkbox" onclick="togglePassword('password')"> <small>Show Password</small>
                            <?php output(@$validationMsg['password']) ?>
                        </label><br><br>
                        <label>
                            First Name
                            <input aria-required="true" type="text" name="fname" placeholder="Please type fname..." maxlength="50" value="<?= (isset($queryOne[0]->fname)) ? $queryOne[0]->fname : output(@$fname) ?>">
                            <?php output(@$validationMsg['fname']) ?>
                        </label><br><br>
                        <label>
                            Last Name
                            <input aria-required="true" type="text" name="lname" placeholder="Please type lname..." maxlength="50" value="<?= (isset($queryOne[0]->lname)) ? $queryOne[0]->lname : output(@$lname) ?>">
                            <?php output(@$validationMsg['lname']) ?>
                        </label><br><br>
                        <label>
                            Date of Birth
                            <input type="date" name="dob" aria-required="true" maxlength="20" value="<?= (isset($queryOne[0]->dob)) ? $queryOne[0]->dob : output(@$dob) ?>"/>
                            <?php output(@$validationMsg['dob']) ?>
                        </label><br><br>

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

        <?php
                } else {

                    // if the id type is valid but doesn't exist
                    echo '<h1>Oops something happen!</h1>';
                    $validationMsg['id'] = errMsg('Unindentified id!');
                    echo output(@$validationMsg['id']);
                    echo '<h3>We cannot process your request please click <a href="swimmersList.php">here</a> to go back to previous menu</h3>';

                }

            } else {

                 // if the id doesn't isset
                echo '<h1>Oops something happen!</h1>';
                $validationMsg['id'] = errMsg('Unindentified id!');
                echo output(@$validationMsg['id']);
                echo '<h3>We cannot process your request please click <a href="swimmersList.php">here</a> to go back to previous menu</h3>';

            }
        ?>

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