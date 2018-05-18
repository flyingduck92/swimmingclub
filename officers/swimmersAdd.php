<?php
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

    $expected = array('role_id','username','password','fname','lname','dob','active');
    $validationMsg = array();
    $submittedData = array();


    if($_POST) {

        $_POST['role_id'] = 3;
        $_POST['active'] = 1;

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
                if($field == 'phone') {}
                else if(isRequired($field)) {
                    $validationMsg[$field] = errMsg('*Required!');
                }
            }
        }

        // print_r($submittedData);
        // print_r($validationMsg);

        // if email exist insert
        if(empty($validationMsg)) {

            // Encrypt Password
            $options = [ 'cost' => 12 ];
            $submittedData['password'] = password_hash($submittedData['password'], PASSWORD_BCRYPT, $options);

            $swimmerData = array(
                    'role_id' => 3,
                    'username' => $submittedData['username'],
                    'password' => $submittedData['password'],
                    'fname' => $submittedData['fname'],
                    'lname' => $submittedData['lname'],
                    'dob' => $submittedData['dob'],
                    'email' => $submittedData['email'],
                    'active' => 1
            );

            // get swimmer key 
            $swimmerField   = implode(",", array_keys($swimmerData));
            $swimmerValue   = ":".implode(",:", array_keys($swimmerData));

            // print_r($swimmerData);
            // echo 'INSERT INTO swimmers('.$swimmerField.') VALUES ('.$swimmerValue.')';

            $swimmerInsert  = query('INSERT INTO swimmers('.$swimmerField.') VALUES ('.$swimmerValue.')', $swimmerData);

            if($swimmerInsert) {

                // send email if localhost (development)
                if($_SERVER['SERVER_NAME'] == 'localhost') {
                    //  then send credential through email (localhost)
                    send_email($submittedData['email'], "New Swimmer Credential", 
                    "\nHello we just add new swimmer to your account
                    \n\nThis is your new swimmer credential: 
                    \nUsername : ".$submittedData['username']." 
                    \nPassword : ".$_POST['password']." 
                    \n\nPlease click go to below to login 
                    \nhttp://".$_SERVER['SERVER_NAME']."/pwa/login.php
                    \n\nWarning: Do not share the login credential 
                    \n-Staffordshire Swimming Club-"
                    );
                    
                //  then send credential through email (hosting)
                } else {
                    send_email($submittedData['email'], "New Swimmer Credential", 
                    "\nHello we just add new swimmer to your account
                    \n\nThis is your new swimmer credential: 
                    \nUsername : ".$submittedData['username']." 
                    \nPassword : ".$_POST['password']." 
                    \n\nPlease click go to below to login 
                    \nhttp://".$_SERVER['SERVER_NAME']."/login.php
                    \n\nWarning: Do not share the login credential 
                    \n-Staffordshire Swimming Club-"
                    );

                }

                header('location: swimmersList.php?success');
            }
        } // end
        
    }

 ?>

    <main id="main-content">
        
        <h1><a href="swimmersList.php">Swimmer List</a> / Add Swimmer</h1>
        <hr><br>

        <form method="post" action="swimmersAdd.php">

            <label>
                Username
                <input required aria-required="true" type="text" name="username" placeholder="Please type username..." maxlength="50"  value="<?php output(@$username) ?>">
                <?php output(@$validationMsg['username']) ?>
            </label><br><br>
            <label>
                Password
                <input required aria-required="true" maxlength="25" id="password" type="password" name="password" placeholder="Please type password..." value="<?php output(@$password) ?>">
                <input type="checkbox" onclick="togglePassword('password')"> <small>Show Password</small>
                <?php output(@$validationMsg['password']) ?>
            </label><br><br>
            <label>
                First Name
                <input required aria-required="true" type="text" name="fname" placeholder="Please type fname..." maxlength="20"  value="<?php output(@$fname) ?>">
                <?php output(@$validationMsg['fname']) ?>
            </label><br><br>
            <label>
                Last Name
                <input required aria-required="true" type="text" name="lname" placeholder="Please type lname..." maxlength="20"  value="<?php output(@$lname) ?>">
                <?php output(@$validationMsg['lname']) ?>
            </label><br><br>
            <label>
                Date of Birth
                <input required type="date" name="dob" aria-required="true" maxlength="20" value="<?php output(@$dob) ?>"/>
                <?php output(@$validationMsg['dob']) ?>
            </label><br><br>
            <label>
                Email:
                <input required style="width: 200px;" list='email' type="text" name="email" value="<?php output(@$email) ?>">
                <?php output(@$validationMsg['email']) ?>
            </label><br><br>

            <datalist id="email">
            <?php 
                // getAllEmails
                $result = query("SELECT email from parents ORDER BY email");
                
                for($i = 0; $i<count($result);$i++) {
                    echo '<option value="'.$result[$i]->email.'">';
                }

             ?>    
            </datalist>
            
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