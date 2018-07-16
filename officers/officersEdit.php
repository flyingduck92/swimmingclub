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

    $expected = array('id','password','fname','lname','dob','active');
    $validationMsg = array();

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
                if($field == 'password' || $field == 'active') { }
                else if(isRequired($field)) {
                    $validationMsg[$field] = errMsg('*Required!');
                }
            }
        }

        // print_r($_POST);
        // print_r($validationMsg);

        // if no error
        if(empty($validationMsg)) {

            $officerData;
            $officerUpdate;

            if(empty($_POST['password'])) {

                $officerData = array(
                        'id' => $_POST['id'],
                        'fname' => $_POST['fname'],
                        'lname' => $_POST['lname'],
                        'dob' => $_POST['dob'],
                        'active' => $_POST['active']
                );

                // print_r($officerData);

                $officersUpdate  = query('UPDATE officers
                                   SET fname = :fname,
                                       lname = :lname,
                                       dob = :dob,
                                       active = :active
                                   WHERE id = :id', $officerData);

            // if password not empty
            } else {
                // Encrypt Password
                $options = [ 'cost' => 12 ];
                $_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);

                $officerData = array(
                        'id' => $_POST['id'],
                        'password' => $_POST['password'],
                        'fname' => $_POST['fname'],
                        'lname' => $_POST['lname'],
                        'dob' => $_POST['dob'],
                        'active' => $_POST['active']
                );

                $officersUpdate  = query('UPDATE officers
                                   SET password = :password,
                                       fname = :fname,
                                       lname = :lname,
                                       dob = :dob,
                                       active = :active
                                   WHERE id = :id', $officerData);

            }

            if($officersUpdate) {
                header('location: officersList.php?update_success');
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
                    echo '<h3>We cannot process your request please click <a href="officersList.php">here</a> to go back to previous menu</h3>';
                }

            }
        ?>

        <?php
            // if no error load
            // print_r($validationMsg);
            if(empty($validationMsg['id']) && isset($_GET['id'])) {
                $queryOne = query('SELECT * FROM officers WHERE id = :id', array('id'=>(int)$_GET['id']));

                // print_r($queryOne);

                // if found data
                if($queryOne) {

        ?>
                    <h1><a href="officersList.php">Officer List</a> / Edit Officer</h1>
                    <hr>

                    <form data-ready="false" method="post" action="officersEdit.php?id=<?= $_GET['id'] ?>">

                        <label>
                            Officer ID
                            <input readonly required aria-required="true" type="text" name="id" placeholder="Please type id..." maxlength="20"  value="<?= (isset($queryOne[0]->id)) ? $queryOne[0]->id : output(@$id) ?>">
                            <?php output(@$validationMsg['id']) ?>
                        </label><br><br>
                        <label>
                            Username <b><?= $queryOne[0]->username ?></b>
                        </label><br><br>
                        <label>
                            Password
                            <input id="password" type="password" name="password" placeholder="Please type password..." maxlength="20"  value="<?= output(@$password) ?>">
                            <input type="checkbox" onclick="togglePassword('password')"> <small>Show Password</small>
                            <?php output(@$validationMsg['password']) ?>
                        </label><br><br>
                        <label>
                            First Name
                            <input type="text" placeholder="Input your first name..." name="fname" required aria-required="true" maxlength="20" value="<?= (isset($queryOne[0]->fname)) ? $queryOne[0]->fname : output(@$fname) ?>">
                            <?php output(@$validationMsg['fname']) ?>
                        </label><br><br>
                        <label>
                            Last Name
                            <input type="text" placeholder="Input your last name..." name="lname" required aria-required="true" maxlength="50" value="<?= (isset($queryOne[0]->lname)) ? $queryOne[0]->lname : output(@$lname) ?>"/>
                            <?php output(@$validationMsg['lname']) ?>
                        </label><br><br>
                        <label>
                            Date of Birth
                            <input type="date" placeholder="Input your Date of Birth" name="dob" required aria-required="true" value="<?= (isset($queryOne[0]->dob)) ? $queryOne[0]->dob : output(@$dob) ?>" />
                            <?php output(@$validationMsg['dob']) ?>
                        </label><br><br>
                        <label>
                            Status &nbsp;
                            <label class="switch">
                                <input name="active" type="checkbox" <?= ($queryOne[0]->active) ? 'checked':'' ?> />
                                <span class="slider"></span>
                            </label><br><br>
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
                    echo '<h3>We cannot process your request please click <a href="officersList.php">here</a> to go back to previous menu</h3>';

                }

            } else {

                    // if the id doesn't isset
                    echo '<h1>Oops something happen!</h1>';
                    $validationMsg['id'] = errMsg('Unindentified id!');
                    echo output(@$validationMsg['id']);
                    echo '<h3>We cannot process your request please click <a href="officersList.php">here</a> to go back to previous menu</h3>';

                }
        ?>

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