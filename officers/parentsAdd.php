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

    $expected = array('role_id','email','parent_name','phone','address','postcode','password','active');
    $validationMsg = array();
    $submittedData = array();

    if($_POST) {

        $_POST['role_id'] = 2;
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

        // if no error
        if(empty($validationMsg)) {

            // Encrypt Password
            $options = [ 'cost' => 12 ];
            $submittedData['password'] = password_hash($submittedData['password'], PASSWORD_BCRYPT, $options);

             // if phone not isset set phone to empty
            $submittedData['phone'] = (!isset($submittedData['phone'])) ? '': $submittedData['phone'];

            $parentData = array(
                    'role_id' => 2,
                    'email' => $submittedData['email'],
                    'parent_name' => $submittedData['parent_name'],
                    'phone' => $submittedData['phone'],
                    'address' => $submittedData['address'],
                    'postcode' => $submittedData['postcode'],
                    'password' => $submittedData['password'],
                    'active' => 1
            );

            // get parent key 
            $parentField   = implode(",", array_keys($parentData));
            $parentValue   = ":".implode(",:", array_keys($parentData));

            $parentInsert  = query('INSERT INTO parents('.$parentField.') VALUES ('.$parentValue.')', $parentData);

            if($parentInsert) {

                // send email if localhost (development)
                if($_SERVER['SERVER_NAME'] == 'localhost') {
                    //  then send credential through email (localhost)
                    send_email($submittedData['email'], "Login Credential", 
                    "\nHello ".$submittedData['parent_name']."
                    \n\nTo login as a parent use: 
                    \nEmail : ".$submittedData['email']." 
                    \nPassword : ".$_POST['password']." 
                    \n\nPlease click go to below to login as parent 
                    \nhttp://".$_SERVER['SERVER_NAME']."/pwa/login.php
                    \n\nWarning: Do not share your login credential 
                    \n-Staffordshire Swimming Club-"
                    );
                    
                //  then send credential through email (hosting)
                } else {
                     send_email($submittedData['email'], "Login Credential", 
                    "\nHello ".$submittedData['parent_name']."
                    \n\nTo login as a parent use: 
                    \nEmail : ".$submittedData['email']." 
                    \nPassword : ".$_POST['password']." 
                    \n\nPlease click go to below to login as parent 
                    \nhttp://".$_SERVER['SERVER_NAME']."/login.php
                    \n\nWarning: Do not share your login credential 
                    \n-Staffordshire Swimming Club-"
                    );

                }

                header('location: parentsList.php?success');
            }
        }
        
    }

 ?>

    <main id="main-content">
        
        <h1><a href="parentsList.php">Parent List</a> / Add Parent</h1>
        <hr><br>

        <form method="post" action="parentsAdd.php">

            <label>
                Email
                <input required aria-required="true" type="text" name="email" placeholder="Please type email..." maxlength="50"  value="<?php output(@$email) ?>">
                <?php output(@$validationMsg['email']) ?>
            </label><br><br>
            <label>
                Full Name
                <input required aria-required="true" type="text" name="parent_name" placeholder="Please type parent_name..." maxlength="20"  value="<?php output(@$parent_name) ?>">
                <?php output(@$validationMsg['parent_name']) ?>
            </label><br><br>
            <label>
                Phone
                <input type="text" placeholder="Input phone..." name="phone" maxlength="20" value="<?php output(@$phone) ?>"/>
                <?php output(@$validationMsg['phone']) ?>
            </label><br><br>
            <label>
                Address
                <input type="text" placeholder="Input address..." name="address" required aria-required="true" maxlength="20" value="<?php output(@$address) ?>"/>
                <?php output(@$validationMsg['address']) ?>
            </label><br><br>
            <label>
                Postcode
                <input type="text" placeholder="Input postcode..." name="postcode" required aria-required="true" maxlength="20" value="<?php output(@$postcode) ?>"/>
                <?php output(@$validationMsg['postcode']) ?>
            </label><br><br>
            <label>
                Password
                <input required aria-required="true" maxlength="25" id="password" type="password" name="password" placeholder="Please type password..." value="<?php output(@$password) ?>">
                <input type="checkbox" onclick="togglePassword('password')"> <small>Show Password</small>
                <?php output(@$validationMsg['password']) ?>
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