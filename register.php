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
    $title = 'Register Page';

    // template
    include './inc/header.php'; //header
    include './inc/nav.php'; // navigation

    /*getting all VALIDATION on for registeration on registerPageFunc*/
    include 'core/function/registerPageFunc.php';

    $expected = array('username', 'fname', 'lname', 'dob', 'email', 'parentName', 'phone', 'address', 'postcode', 'password','password2');
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

        // password & password2 compare
        if($submittedData['password'] != $submittedData['password2']) {
            $validationMsg['password2'] = errMsg('Passwords did not match!');
        }
        // if error show notification to amend the registration form
        if($validationMsg) {
            $validationMsg['form'] = errMsg('Please amend the required field');

        } else {
            // if everything is valid register data and send email

            // remove the password2 from array
            unset($submittedData['password2']);

            // setup cost bcrypt and hash the password
            $options = [ 'cost' => 12 ];
            $submittedData['password'] = password_hash($submittedData['password'], PASSWORD_BCRYPT, $options);

            // parents 7 column
            $parentsData = array(
                'role_id'     => 2,
                'email'       => $submittedData['email'],
                'parent_name' => $submittedData['parentName'],
                'phone'       => $submittedData['phone'],
                'address'     => $submittedData['address'],
                'postcode'    => $submittedData['postcode'],
                'password'    => $submittedData['password']
            );

            // swimmers 6 column
            $swimmersData = array(
                'role_id'   => 3,
                'username'  => $submittedData['username'],
                'password'  => $submittedData['password'],
                'fname'     => $submittedData['fname'],
                'lname'     => $submittedData['lname'],
                'dob'       => $submittedData['dob'],
                'email'     => $submittedData['email']
            );

            // get parent key
            $parentsField   = implode(",", array_keys($parentsData));
            $parentsValue   = ":".implode(",:", array_keys($parentsData));
            // get swimmer key
            $swimmersField = implode(",", array_keys($swimmersData));
            $swimmersValue = ":".implode(",:", array_keys($swimmersData));

            // insert to database
            $parentsRegister    = query('INSERT INTO parents('.$parentsField.') VALUES ('.$parentsValue.')', $parentsData);
            $swimmersRegister   = query('INSERT INTO swimmers('.$swimmersField.') VALUES ('.$swimmersValue.')', $swimmersData);

            if($parentsRegister && $swimmersRegister) {

                // send email if localhost
                if($_SERVER['SERVER_NAME'] == 'localhost') {
                    //  then send email for activation
                    send_email($submittedData['email'], "Activate account",
                    "\nHello ".$submittedData['fname']." ".$submittedData['lname']."
                    \n\nPlease note password for parent and swimmer are the same
                    \n\nPlease click the link below to activate the accounts (check inbox or spam directory)
                    \n\nhttp://".$_SERVER['SERVER_NAME']."/pwa/activate.php?email=".$submittedData['email']."&username=".$submittedData['username']."
                    \n\n -Staffordshire Swimming Club-"
                    );

                //  send email if hosting
                } else {
                    send_email($submittedData['email'], "Activate account",
                    "\nHello ".$submittedData['fname']." ".$submittedData['lname']."
                    \n\nPlease note password for parent and swimmer are the same
                    \n\nPlease click the link below to activate the accounts (check inbox or spam directory)
                    \n\nhttp://".$_SERVER['SERVER_NAME']."/activate.php?email=".$submittedData['email']."&username=".$submittedData['username']."
                    \n\n -Staffordshire Swimming Club-"
                    );

                }

                // if email have been send redirect to success
                header('location: register.php?success');
            }

        }
    }

 ?>

    <main class="box main">

       <form action="register.php" method="POST">
            <fieldset>
                <legend><h1>Register</h1></legend>
                <?php
                    if(isset($_GET['success']) && empty($_GET['success'])) {
                        $validationMsg['form'] = successMsg('Registration Success. Please check your email for activation.');
                        output(@$validationMsg['form']);
                        echo "<br>";
                    }
                    else {
                        output(@$validationMsg['form']);
                    }
                ?>
                <p>
                    <b>Username</b><br>
                    <input type="text" placeholder="Input your Username" name="username" required aria-required="true" maxlength="20" value="<?php output(@$username) ?>"/>
                    <br>
                    <?php output(@$validationMsg['username']) ?>
                </p>

                <p>
                    <b>First Name</b><br>
                    <input type="text" placeholder="Input your First name" name="fname" required aria-required="true" maxlength="20" value="<?php output(@$fname) ?>"/>
                    <br>
                    <?php output(@$validationMsg['fname']) ?>
                </p>

                <p>
                    <b>Last Name</b><br>
                    <input type="text" placeholder="Input your Last name" name="lname" required aria-required="true" maxlength="50" value="<?php output(@$lname) ?>"/>
                    <br>
                    <?php output(@$validationMsg['lname']) ?>
                </p>

                <p>
                    <b>Date of Birth</b><br>
                    <input type="date" placeholder="Input your Date of Birth" name="dob" required aria-required="true" value="<?php output(@$dob) ?>" />
                    <br>
                    <?php output(@$validationMsg['dob']) ?>
                </p>

                <p>
                    <b>Email Address</b><br>
                    <input type="text" placeholder="Input your Email" name="email" required aria-required="true" size="35" maxlength="80" value="<?php output(@$email) ?>"/>
                    <br>
                    <?php output(@$validationMsg['email']) ?>
                </p>

                <p>
                    <b>Parent Name</b><br>
                    <input type="text"  placeholder="Input your Parent full name" name="parentName" required aria-required="true" maxlength="40" value="<?php output(@$parentName) ?>"/>
                    <br>
                    <?php output(@$validationMsg['parentName']) ?>
                </p>

                <p>
                    <b>Phone</b><br>
                    <input type="text" placeholder="Input your Phone number" name="phone" required aria-required="true" value="<?php output(@$phone) ?>" />
                    <br>
                    <?php output(@$validationMsg['phone']) ?>
                </p>

                <p>
                    <b>Address</b><br>
                    <input type="text" placeholder="Input your Address" name="address" required aria-required="true" value="<?php output(@$address) ?>" />
                    <br>
                    <?php output(@$validationMsg['address']) ?>
                </p>

                 <p>
                    <b>Post code</b><br>
                    <input type="text" placeholder="Input your Post Code" name="postcode" required aria-required="true" value="<?php output(@$postcode) ?>" />
                    <br>
                    <?php output(@$validationMsg['postcode']) ?>
                </p>

                <p>
                    <b>Password</b><br>
                    <input id='password' placeholder="Input your Password (must be between 4-25 characters)" type="password" name="password" required aria-required="true" maxlength="25" value="<?php output(@$password) ?>" /><br>
                    <input type="checkbox" onclick="togglePassword('password')"> <small>Show Password</small>
                    <br>
                    <?php output(@$validationMsg['password']) ?>
                </p>

                <p>
                    <b>Confirm Password</b><br>
                    <input id='password2' placeholder="Input your Password again (should be match)" type="password" name="password2" required aria-required="true" maxlength="25" value="<?php output(@$password2) ?>" /><br>
                    <input type="checkbox" onclick="togglePassword('password2')"> <small>Show Password</small>
                    <br>
                    <?php output(@$validationMsg['password2']) ?>
                </p>
            </fieldset>

            <div align="center">
                <button type="submit" class="btn-register">Register</button>
            </div>
        </form>
    </main>

<?php
    // template footer
    include './inc/footer.php';

?>
