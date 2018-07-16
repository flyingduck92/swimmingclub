<?php
    ob_start();
    include 'core/init.php';

    // check loggedIn or not
    if (loggedIn() && $_SESSION['role_id'] == 1) {
        header('Location: officers/index.php');
        exit();

    } elseif (loggedIn() && $_SESSION['role_id'] == 2) {
        header('Location: parents/index.php');
        exit();

    }  elseif (loggedIn() && $_SESSION['role_id'] == 3) {
        header('Location: swimmers/index.php');
        exit();

    }

    if(connect() == false) {
        header('Location: index.php');
    }

    $header = 'Staffordshire Swimming Club';
    $title = 'Login Page';

    // template
    include './inc/header.php'; //header
    include './inc/nav.php'; // navigation

    $expected = array('username','password');
    $validationMsg = array();
    $submittedData = array();

    /*getting all VALIDATION on for login on loginPageFunc*/
    include 'core/function/loginPageFunc.php';

    if($_POST) {

        foreach ($expected as $field) {
            $value = trim($_POST[$field]);
            if(isNotEmpty($value)) {
                ${$field} = htmlentities($value, ENT_COMPAT, 'UTF-8');
                $submittedData[$field] = $value;
            } else {
                if(isRequired($field)) {
                    $validationMsg[$field] = errMsg('*Required!');
                }
            }
        }

        if($validationMsg){
            $validationMsg['form'] = errMsg('Please amend the required field');

        } else {

            // check in officers and swimmers (do union)
            $swimmersOfficersCheck = query('SELECT username FROM swimmers WHERE username = :username
                   UNION
                   SELECT username FROM officers WHERE username = :username2',
                   array('username'  => $submittedData['username'],
                         'username2' => $submittedData['username']));

            // if username exists, verify password
            if ($swimmersOfficersCheck) {
                $passwordAndRoleFromDB = query('SELECT password, role_id FROM swimmers WHERE username = :username
                                   UNION
                                   SELECT password, role_id FROM officers WHERE username = :username2',
                                   array('username'  => $submittedData['username'],
                                         'username2' => $submittedData['username']))[0];

                if(password_verify($submittedData['password'], $passwordAndRoleFromDB->password)) {
                    // if correct

                    // check active or not
                    $isActive = query('SELECT username FROM swimmers WHERE username = :username AND active = 1
                                       UNION
                                       SELECT username FROM officers WHERE username = :username2 AND active = 1',
                                       array('username'  => $submittedData['username'],
                                             'username2' => $submittedData['username']));

                    // if'active' redirect
                    if(count($isActive) > 0) {
                        // go to officers
                        if($passwordAndRoleFromDB->role_id == 1) {
                            $_SESSION['username'] = $submittedData['username'];
                            $_SESSION['role_id'] = $passwordAndRoleFromDB->role_id;
                            header('Location: officers/index.php');

                        }
                        // go to swimmers
                        if ($passwordAndRoleFromDB->role_id == 3) {
                            $_SESSION['username'] = $submittedData['username'];
                            $_SESSION['role_id'] = $passwordAndRoleFromDB->role_id;
                            header('Location: swimmers/index.php');
                        }

                    } else {
                        // 'have not activated yet'
                         $validationMsg['form'] = errMsg('You have not activate the account. Please check your email to activate your account');
                    }

                } else {
                    // incorrect password
                    $validationMsg['form'] = errMsg('Incorrect Password! Please try again...');
                }

            } else {

                // 'check in parent';
                $parentsCheck = query('SELECT email FROM parents WHERE email = :email',
                                       array('email' => $submittedData['username']));

                // if 'parent exists', verify password
                if ($parentsCheck) {
                    $passwordAndRoleFromDB = query('SELECT password, role_id FROM parents WHERE email = :email',
                                                    array('email' => $submittedData['username']))[0];

                    if(password_verify($submittedData['password'], $passwordAndRoleFromDB->password)) {

                        $isActive = query('SELECT email FROM parents WHERE email = :email AND active = 1',
                                       array('email' => $submittedData['username']));

                        // if active redirect
                        if(count($isActive) > 0) {

                            // set session and redirect to parent directory
                            if($passwordAndRoleFromDB->role_id == 2) {
                                $_SESSION['username'] = $submittedData['username'];
                                $_SESSION['role_id'] = $passwordAndRoleFromDB->role_id;
                                header('Location: parents/index.php');
                            }

                        } else {
                            // 'have not activated yet'
                             $validationMsg['form'] = errMsg('You have not activate the account. Please check your email to activate your account');
                        }

                    } else {
                        // incorrect pasword
                        $validationMsg['form'] = errMsg('Incorrect Password! Please try again...');
                    }

                } else {
                    // user does not exists
                    $validationMsg['form'] = errMsg('User does not Exist! Have you registered?');
                }

            }

        }

    }

?>

    <main class="box main">

       <form id="login-page" action="login.php" method="post">
            <fieldset>
                <legend><h1>Login</h1></legend>
                <?php output(@$validationMsg['form']) ?>
                <p>
                    <b>Username</b><br>
                    <input type="text" placeholder="Input your Username" name="username" required aria-required="true" maxlength="80" value="<?php output(@$username) ?>" /><br>
                    <?php output(@$validationMsg['username']) ?>
                </p>

                <p>
                    <b>Password</b><br>
                    <input id="password" type="password" placeholder="Input your Password" name="password" required aria-required="true" maxlength="25" value="<?php output(@$password) ?>"/><br>
                    <input type="checkbox" onclick="togglePassword('password')"> <small>Show Password</small>
                    <?php output(@$validationMsg['password']) ?>
                </p>

            </fieldset>

            <div align="center">
                <button class="btn-login" type="submit">Login</button>
            </div>
        </form>
    </main>

<?php
    // template footer
    include './inc/footer.php';
?>
