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

    $expected = array('username','password','fname','lname','dob');
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

        // if no error
        if(empty($validationMsg)) {

            // Encrypt Password
            $options = [ 'cost' => 12 ];
            $submittedData['password'] = password_hash($submittedData['password'], PASSWORD_BCRYPT, $options);

            $officerData = array(
                    'role_id' => 1,
                    'username' => $submittedData['username'],
                    'password' => $submittedData['password'],
                    'fname' => $submittedData['fname'],
                    'lname' => $submittedData['lname'],
                    'dob' => $submittedData['dob'],
                    'active' => 1
            );

            // get parent key 
            $officerField   = implode(",", array_keys($officerData));
            $officerValue   = ":".implode(",:", array_keys($officerData));

            $officersInsert  = query('INSERT INTO officers('.$officerField.') VALUES ('.$officerValue.')', $officerData);

            if($officersInsert) {
                header('location: officersList.php?success');
            }
        }
        
    }

 ?>

    <main id="main-content">
        
        <h1><a href="officersList.php">Officer List</a> / Add Officer</h1>
        <hr><br>

        <form method="post" action="officersAdd.php">

            <label>
                Username
                <input required aria-required="true" type="text" name="username" placeholder="Please type username..." maxlength="20"  value="<?php output(@$username) ?>">
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
                <input type="text" placeholder="Input your first name..." name="fname" required aria-required="true" maxlength="20" value="<?php output(@$fname) ?>"/>
                <?php output(@$validationMsg['fname']) ?>
            </label><br><br>
            <label>
                Last Name
                <input type="text" placeholder="Input your last name..." name="lname" required aria-required="true" maxlength="50" value="<?php output(@$lname) ?>"/>
                <?php output(@$validationMsg['lname']) ?>
            </label><br><br>
            <label>
                Date of Birth
                <input type="date" placeholder="Input your Date of Birth" name="dob" required aria-required="true" value="<?php output(@$dob) ?>" />
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