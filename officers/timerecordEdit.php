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

    $expected = array('id','gala_id','line_number','swimmer_name','recordtime','finish_number');
    $validationMsg = array();
    $submittedData = array();

    // groupEdit.php?id=32
    if($_POST) {

        // print_r($_POST);

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

        if(empty($validationMsg)) {
            $updateTimeRecord = query('UPDATE timerecords 
                                  SET line_number = :line_number, swimmer_name = :swimmer_name, recordtime = :recordtime, finish_number = :finish_number  
                                  WHERE id = :id AND gala_id = :gala_id',$submittedData);
            if($updateTimeRecord) { 
                header('Location: galaView.php?id='.$submittedData['gala_id'].'&update_success');
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
                    echo '<h3>We cannot process your request please click <a href="galaList.php">here</a> to go back to previous menu</h3>';
                } 
                
            }
        ?>

        <?php 
            // if no error load
            // print_r($validationMsg);
             if(empty($validationMsg['id']) && isset($_GET['id'])) {
                $queryOne = query('SELECT * FROM timerecords WHERE id = :id', array('id'=>$id));

                // if found data
                if($queryOne) {
                    // print_r($queryOne);

        ?>  
                    <h1><a href="galaView.php?id=<?= $_GET['gala_id']; ?>">Back</a> / Edit timerecords</h1>
                    <hr>

                    <form method="post" action="timerecordEdit.php?id=<?= $_GET['id']; ?>">
                        <p><b>Note:</b> Only accept letters and numbers</p>
                        
                        <label>
                            Gala Id:
                            <input style="width: 50px;" required type="text" name="gala_id" readonly value="<?= $queryOne[0]->gala_id ?>">
                            <?php output(@$validationMsg['gala_id']) ?>
                        </label><br><br>
                        <label>
                            Time Records Id:
                            <input style="width: 50px;" required type="text" name="id" readonly value="<?= $queryOne[0]->id ?>">
                            <?php output(@$validationMsg['id']) ?>
                        </label><br><br>
                        <label>
                            Line Number:
                            <input required type="number" name="line_number" min="1" max="5" placeholder="1-5" value="<?= $queryOne[0]->line_number ?>">
                            <?php output(@$validationMsg['line_number']) ?>
                        </label><br><br>
                        <label>
                            Swimmer Name:
                            <input list='swimmername' required type="text" name="swimmer_name" value="<?= $queryOne[0]->swimmer_name ?>">
                            <?php output(@$validationMsg['swimmer_name']) ?>
                        </label>

                        <datalist id="swimmername">
                        <?php 
                            // getAllSwimmer
                            $result = query("SELECT UPPER(CONCAT(lname,', ', fname)) as swimmer_name from swimmers");
                            
                            for($i = 0; $i<count($result);$i++) {
                                echo '<option value="'.$result[$i]->swimmer_name.'">';
                            }

                         ?>    
                        </datalist><br><br>
                        <label for="time1">Time Record:
                            <input style="width: 130px" required id="time1" type="text" name="recordtime" value="<?= $queryOne[0]->recordtime ?>">
                            <?php output(@$validationMsg['recordtime']) ?>
                        </label><br><br>   
                        <label>
                            Finish Number:
                            <input required type="number" name="finish_number" min="0" max="5" placeholder="0-5" value="<?= $queryOne[0]->finish_number ?>">
                            <?php output(@$validationMsg['finish_number']) ?>
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
                    echo '<h3>We cannot process your request please click <a href="galaList.php">here</a> to go back to previous menu</h3>';

                } 
            
             } else {

                    // if the id doesn't isset 
                    echo '<h1>Oops something happen!</h1>';    
                    $validationMsg['id'] = errMsg('Unindentified id!');        
                    echo output(@$validationMsg['id']);
                    echo '<h3>We cannot process your request please click <a href="galaList.php">here</a> to go back to previous menu</h3>';

                } 
        ?>

    </main>

<?php 
    
    include '../inc/loggedIn_footer.php';

 ?>