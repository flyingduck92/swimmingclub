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

    $expected = array('id','category_name','heatfinal_desc','date','group_name','event_name','venue_name','note');
    $validationMsg = array();
    $submittedData = array();

    // groupEdit.php?id=32
    if($_POST) {

        print_r($_POST);

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
                if($field == 'note') {}
                else if(isRequired($field)) {
                    $validationMsg[$field] = errMsg('*Required!');
                }
            }
        }

        $galaData = $submittedData;
        $galaData['note'] = (isset($galaData['note'])) ? $galaData['note'] : '';

        $galaUpdate  = query('UPDATE gala 
                              SET category_name = :category_name, 
                                  heatfinal_desc = :heatfinal_desc, 
                                  date = :date, 
                                  group_name = :group_name,   
                                  event_name = :event_name,   
                                  venue_name = :venue_name,   
                                  note = :note   
                              WHERE id = :id', $galaData);

        if($galaUpdate) {
            header('location: galaList.php?update_success');
        }
        
    }
    
 ?>

    <main id="main-content">

        <?php 
            // get data from page load where id 
            if($_GET) {

                $id = trim((int)$_GET['id']);
                $id = htmlentities($id, ENT_COMPAT, 'UTF-8');
               
                if($message = typePatternCheck('id', $id)) {
                    $validationMsg['id'] = errMsg($message);
                }

                if($validationMsg) { 
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
                $queryOne = query('SELECT * FROM gala WHERE id = :id', array('id'=>$id));

                // if found data
                if($queryOne) {

        ?>  
                <h1><a href="galaList.php">Back</a> / Edit Gala </h1>
                    <hr>

                    <form method="post" action="galaEdit.php">
                        <label>
                            Gala Id:
                            <input style="width: 50px;" required type="text" name="id" readonly value="<?= $queryOne[0]->id ?>">
                            <?php output(@$validationMsg['id']) ?>
                        </label><br><br>
                        <label>
                            Gala Category:
                            <input required list="category" type="text" name="category_name" value="<?= $queryOne[0]->category_name ?>">

                            <datalist id="category">
                            <?php 
                                // getAllSwimmer
                                $result = query("SELECT name from categories");
                                
                                for($i = 0; $i<count($result);$i++) {
                                    echo '<option value="'.$result[$i]->name.'">';
                                }

                             ?>    
                            </datalist>
                        </label><br><br>
                        <label>
                            Heat/Final:
                            <input required list="heatfinal" type="text" name="heatfinal_desc" value="<?= $queryOne[0]->heatfinal_desc ?>">

                            <datalist id="heatfinal">
                            <?php 
                                // getAllSwimmer
                                $result = query("SELECT description from heat_final");
                                
                                for($i = 0; $i<count($result);$i++) {
                                    echo '<option value="'.$result[$i]->description.'">';
                                }

                             ?>    
                            </datalist>
                        </label><br><br>
                        <label>
                            Date:
                            <input required type="date" name="date" value="<?= $queryOne[0]->date ?>">
                        </label><br><br>
                        <label>
                            Group Name:
                            <select name="group_name" value="<?= $queryOne[0]->group_name ?>">
                                <option value="" disabled>Select group</option>
                            <?php 
                                // getAllSwimmer
                                $result = query("SELECT name from groups");
                                for($i = 0; $i<count($result);$i++) {
                                    echo '<option value="'.$result[$i]->name.'">'.$result[$i]->name.'</option>';
                                }
                             ?>    
                            </select>
                        </label><br><br>  
                        <label>
                            Event Name:
                            <select name="event_name" value="<?= $queryOne[0]->event_name ?>">
                                <option value="" disabled>Select Event</option>
                            <?php 
                                // getAllSwimmer
                                $result = query("SELECT event_name from events");
                                for($i = 0; $i<count($result);$i++) {
                                    echo '<option value="'.$result[$i]->event_name.'">'.$result[$i]->event_name.'</option>';
                                }
                             ?>    
                            </select>
                        </label><br><br>
                        <label>
                            Venue Name:
                            <select name="venue_name" value="<?= $queryOne[0]->venue_name ?>">
                                <option value="" disabled>Select Venue</option>
                            <?php 
                                // getAllSwimmer
                                $result = query("SELECT name from venue");
                                for($i = 0; $i<count($result);$i++) {
                                    echo '<option value="'.$result[$i]->name.'">'.$result[$i]->name.'</option>';
                                }
                             ?>    
                            </select>
                        </label><br><br>
                        <label>
                            Note:
                            <input type="text" name="note" value="<?= $queryOne[0]->note ?>">
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