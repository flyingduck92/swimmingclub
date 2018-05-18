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

    $expected = array('id','category_name');
    $validationMsg = array();
    $submittedData = array();

    // categoryEdit.php?id=32
    if($_POST) {

        // print_r($_POST);

        // checking all required field
        foreach ($expected as $field) {           
            $value = trim($_POST[$field]);

            if(isNotEmpty($value)) {
                ${$field} = htmlentities($value, ENT_COMPAT, 'UTF-8');
                // validate field type
                if($message = typePatternCheck($field, $value)) {
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
            $updateCategory = query('UPDATE categories SET name = :name WHERE id = :id', 
                                    array(
                                        'id' => $submittedData['id'],
                                        'name' => $submittedData['category_name']
                                ));
            if($updateCategory) { 
                header('Location: categoryEdit.php?id='.$submittedData['id'].'&update_success');
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
                    echo '<h3>We cannot process your request please click <a href="categoryList.php">here</a> to go back to previous menu</h3>';
                } 
                
            }
        ?>

        <?php 
            // if no error load
            // print_r($validationMsg);
            if(empty($validationMsg['id']) && isset($_GET['id'])) {
                $queryOne = query('SELECT * FROM categories WHERE id = :id', array('id'=>$id));

                // if found data
                if($queryOne) {

        ?>  
                    <h1><a href="categoryList.php">Category List</a> / Group Edit</h1>
                    <hr>

                    <form data-ready="false" method="post" action="categoryEdit.php?id=<?= $id ?>">
                        <p><b>Note:</b> Only accept letters and numbers</p>
                        
                        <label> ID:<br> 
                            <input readonly type="number" name="id" value="<?= $id ?>">
                        </label><br><br>
                        <label> Group Name:<br> 
                            <input required type="text" name="category_name" placeholder="Please input new group" value="<?= $queryOne[0]->name ?>">
                        </label>
                        <br><br>
                        
                        <?php 
                            if(isset($_GET['update_success']) && empty($_GET['update_success'])) {
                                $validationMsg['form'] = successMsg('Update Success');
                                output(@$validationMsg['form']);
                        } 
                        else {
                            output(@$validationMsg['category_name']); 
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
        <?php       
                } else {

                    // if the id type is valid but doesn't exist 
                    echo '<h1>Oops something happen!</h1>';    
                    $validationMsg['id'] = errMsg('Unindentified id!');        
                    echo output(@$validationMsg['id']);
                    echo '<h3>We cannot process your request please click <a href="categoryList.php">here</a> to go back to previous menu</h3>';

                } 
                
             } else {

                 // if the id doesn't isset
                echo '<h1>Oops something happen!</h1>';    
                $validationMsg['id'] = errMsg('Unindentified id!');        
                echo output(@$validationMsg['id']);
                echo '<h3>We cannot process your request please click <a href="categoryList.php">here</a> to go back to previous menu</h3>';


            }
        ?>

    </main>

<?php 
    
    include '../inc/loggedIn_footer.php';

 ?>