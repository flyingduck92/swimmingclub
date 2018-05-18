<?php
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

    $expected = array('email','parent_name','phone','address','postcode');
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
                if ($field == 'email') {}
                else if(isRequired($field)) {
                    $validationMsg[$field] = errMsg('*Required!');
                }
            }
        }

        // print_r($validationMsg);
        // print_r($submittedData);
        
        if(empty($validationMsg)) {

            // if empty email -> use current email
            if(empty($submittedData['email'])) {
                // 'parent_name','phone','address','postcode'
                $updatePassword = query('UPDATE parents 
                                     SET parent_name = :parent_name,  
                                         phone = :phone,  
                                         address = :address,  
                                         postcode = :postcode  
                                     WHERE id = :id', 
                              array(
                                'id' => $userData[0]->id, 
                                'parent_name' => $submittedData['parent_name'],
                                'phone' => $submittedData['phone'],
                                'address' => $submittedData['address'],
                                'postcode' => $submittedData['postcode']
                            )); 

            // if !empty email -> change email
            } else {
                // 'email','parent_name','phone','address','postcode'
                $updatePassword = query('UPDATE parents 
                                     SET email = :email,  
                                         parent_name = :parent_name,  
                                         phone = :phone,  
                                         address = :address,  
                                         postcode = :postcode  
                                     WHERE id = :id', 
                              array(
                                'id' => $userData[0]->id, 
                                'email' => $submittedData['email'],
                                'parent_name' => $submittedData['parent_name'],
                                'phone' => $submittedData['phone'],
                                'address' => $submittedData['address'],
                                'postcode' => $submittedData['postcode']
                            )); 

                // update session using after submit new email
                $_SESSION['username'] = $submittedData['email'];
                $username_from_session = $_SESSION['username'];
                $roleId_from_session = $_SESSION['role_id'];
                
                // getUserData from general.php
                $userData = getUserData($username_from_session, $roleId_from_session);

            }

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
                Current Email <?= $userData[0]->email; ?> <br>
                <b>Note:</b> Type a new email below to change the email <br><br>
                <input aria-required="true" type="text" name="email" placeholder="New email..." maxlength="50"  value="<?= output(@$email) ?>">
                <?php output(@$validationMsg['email']) ?>
            </label><br><br>
            <label>
                Full Name
                <input aria-required="true" type="text" name="parent_name" placeholder="Please type parent_name..." maxlength="50" value="<?= (isset($userData[0]->parent_name)) ? $userData[0]->parent_name : output(@$parent_name) ?>">
                <?php output(@$validationMsg['parent_name']) ?>
            </label><br><br>
            <label>
                Phone
                <input type="text" placeholder="Input phone..." name="phone" aria-required="true" maxlength="20" value="<?= (isset($userData[0]->phone)) ? $userData[0]->phone : output(@$phone) ?>"/>
                <?php output(@$validationMsg['phone']) ?>
            </label><br><br>
            <label>
                Address
                <input type="text" placeholder="Input address..." name="address" aria-required="true" maxlength="20" value="<?= (isset($userData[0]->address)) ? $userData[0]->address : output(@$address) ?>"/>
                <?php output(@$validationMsg['address']) ?>
            </label><br><br>
            <label>
                Postcode
                <input type="text" placeholder="Input postcode..." name="postcode" aria-required="true" maxlength="20" value="<?= (isset($userData[0]->postcode)) ? $userData[0]->postcode : output(@$postcode) ?>"/>
                <?php output(@$validationMsg['postcode']) ?>
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