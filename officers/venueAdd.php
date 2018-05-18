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

    $expected = array('venue_name');
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

        // if no error
        if(empty($validationMsg)) {

            $venueData = array('name' => $submittedData['venue_name']);

            // get parent key 
            $venueField   = implode(",", array_keys($venueData));
            $venueValue   = ":".implode(",:", array_keys($venueData));

            $venueInsert  = query('INSERT INTO venue('.$venueField.') VALUES ('.$venueValue.')', $venueData);

            if($venueInsert) {
                header('location: venueAdd.php?success');
            }
        }
    }
    
 ?>

    <main id="main-content">
        
        <h1><a href="venueList.php">Venue List</a> / Venue Add </h1>
        <hr>

        <form method="post" action="venueAdd.php">
            <p><b>Note:</b> Only accept letters and numbers</p>

            <input required type="text" name="venue_name" placeholder="Please input new group" value="<?php output(@$venue_name) ?>"><br><br>
            <?php 
                if(isset($_GET['success']) && empty($_GET['success'])) {
                        $validationMsg['form'] = successMsg('Venue Successfully added');
                        output(@$validationMsg['form']);
                } 
                else {
                    output(@$validationMsg['venue_name']); 
                }
            ?>
            <br><br>

            <a href="#test"><button type="button" class="info">Submit</button></a>
            
            <!-- Modal for confirmation -->
            <div class="modal" id="test">
                <div class="modal-content">
                    <p>Are you sure?</p><br>
                    <a href="#"><button type="button" class="edit">Cancel</button></a>                            
                    <a><button type="submit" class="info">Yes</button></a>   
                </div>
            </div>
        </form>

    </main>

<?php 
    
    include '../inc/loggedIn_footer.php';

 ?>