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

    $expected = array('id','email','parent_name','phone','address','postcode','password','active');
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
                if($field == 'email' || $field == 'phone' || $field == 'password' || $field == 'active') {}
                else if(isRequired($field)) {
                    $validationMsg[$field] = errMsg('*Required!');
                }
            }
        }

        // print_r($_POST);
        // print_r($validationMsg);

        // if empty email & password
        if(empty($validationMsg)) {
            if(empty($_POST['email']) && empty($_POST['password'])) {
                $submittedData = array(
                    'id' => $_POST['id'],
                    'parent_name' => $_POST['parent_name'],
                    'phone' => $_POST['phone'],
                    'address' => $_POST['address'],
                    'postcode' => $_POST['postcode'],
                    'active' => $_POST['active'],
                );

                $updateParents = query('UPDATE parents
                                      SET parent_name = :parent_name,
                                          phone = :phone,
                                          address = :address,
                                          postcode = :postcode,
                                          active = :active
                                      WHERE id = :id', $submittedData);

            // if not empty email & empty password
            } else if(!empty($_POST['email']) && empty($_POST['password'])) {
                $submittedData = array(
                    'id' => $_POST['id'],
                    'email' => $_POST['email'],
                    'parent_name' => $_POST['parent_name'],
                    'phone' => $_POST['phone'],
                    'address' => $_POST['address'],
                    'postcode' => $_POST['postcode'],
                    'active' => $_POST['active'],
                );

                $updateParents = query('UPDATE parents
                                      SET email = :email,
                                          parent_name = :parent_name,
                                          phone = :phone,
                                          address = :address,
                                          postcode = :postcode,
                                          active = :active
                                      WHERE id = :id', $submittedData);

            // if empty email & not empty password
            } else if(empty($_POST['email']) && !empty($_POST['password'])) {

                // encrypt password
                $options = [ 'cost' => 12 ];
                $_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);

                $submittedData = array(
                    'id' => $_POST['id'],
                    'parent_name' => $_POST['parent_name'],
                    'phone' => $_POST['phone'],
                    'address' => $_POST['address'],
                    'postcode' => $_POST['postcode'],
                    'password' => $_POST['password'],
                    'active' => $_POST['active'],
                );

                $updateParents = query('UPDATE parents
                                      SET parent_name = :parent_name,
                                          phone = :phone,
                                          address = :address,
                                          postcode = :postcode,
                                          password = :password,
                                          active = :active
                                      WHERE id = :id', $submittedData);

            // if not empty email & not empty password
            } else if(!empty($_POST['email']) && !empty($_POST['password'])) {

                // encrypt password
                $options = [ 'cost' => 12 ];
                $_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);

                $submittedData = array(
                    'id' => $_POST['id'],
                    'email' => $_POST['email'],
                    'parent_name' => $_POST['parent_name'],
                    'phone' => $_POST['phone'],
                    'address' => $_POST['address'],
                    'postcode' => $_POST['postcode'],
                    'password' => $_POST['password'],
                    'active' => $_POST['active'],
                );

                $updateParents = query('UPDATE parents
                                      SET email = :email,
                                          parent_name = :parent_name,
                                          phone = :phone,
                                          address = :address,
                                          postcode = :postcode,
                                          password = :password,
                                          active = :active
                                      WHERE id = :id', $submittedData);
            }

            if($updateParents) {
                header('Location: parentsList.php?update_success');
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
                $queryOne = query('SELECT * FROM parents WHERE id = :id', array('id'=>$id));

                // if found data
                if($queryOne) {
                    // echo '***** <br>';
                    // print_r($queryOne);

        ?>
                    <h1><a href="parentsList.php">Back</a> / Edit Parent </h1>
                    <hr>

                    <form method="post" action="parentsEdit.php?id=<?= $queryOne[0]->id ?>">
                        <label>
                            Parent ID
                            <input readonly required type="number" name="id" value="<?= (isset($queryOne[0]->id)) ? $queryOne[0]->id : output(@$id) ?>">
                            <?php output(@$validationMsg['id']) ?>
                        </label><br><br>
                        <label>
                            Current Email <?= $queryOne[0]->email; ?> <br>
                            <b>Note:</b> Type a new email below to change the email <br><br>
                            <input aria-required="true" type="text" name="email" placeholder="New email..." maxlength="50"  value="<?= output(@$email) ?>">
                            <?php output(@$validationMsg['email']) ?>
                        </label><br><br>
                        <label>
                            Full Name
                            <input required aria-required="true" type="text" name="parent_name" placeholder="Please type parent_name..." maxlength="50" value="<?= (isset($queryOne[0]->parent_name)) ? $queryOne[0]->parent_name : output(@$parent_name) ?>">
                            <?php output(@$validationMsg['parent_name']) ?>
                        </label><br><br>
                        <label>
                            Phone
                            <input type="text" placeholder="Input phone..." name="phone" aria-required="true" maxlength="20" value="<?= (isset($queryOne[0]->phone)) ? $queryOne[0]->phone : output(@$phone) ?>"/>
                            <?php output(@$validationMsg['phone']) ?>
                        </label><br><br>
                        <label>
                            Address
                            <input type="text" placeholder="Input address..." name="address" required aria-required="true" maxlength="20" value="<?= (isset($queryOne[0]->address)) ? $queryOne[0]->address : output(@$address) ?>"/>
                            <?php output(@$validationMsg['address']) ?>
                        </label><br><br>
                        <label>
                            Postcode
                            <input type="text" placeholder="Input postcode..." name="postcode" required aria-required="true" maxlength="20" value="<?= (isset($queryOne[0]->postcode)) ? $queryOne[0]->postcode : output(@$postcode) ?>"/>
                            <?php output(@$validationMsg['postcode']) ?>
                        </label><br><br>
                        <label>
                            Password
                            <input aria-required="true" maxlength="25" id="password" type="password" name="password" placeholder="Please type password..." value="<?php output(@$password) ?>">
                            <input type="checkbox" onclick="togglePassword('password')"> <small>Show Password</small>
                            <?php output(@$validationMsg['password']) ?>
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
                    echo '<h3>We cannot process your request please click <a href="parentsList.php">here</a> to go back to previous menu</h3>';

                }

            } else {

                 // if the id doesn't isset
                echo '<h1>Oops something happen!</h1>';
                $validationMsg['id'] = errMsg('Unindentified id!');
                echo output(@$validationMsg['id']);
                echo '<h3>We cannot process your request please click <a href="parentsList.php">here</a> to go back to previous menu</h3>';

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