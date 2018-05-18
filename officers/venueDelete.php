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

    $expected = array('id');

    // heatFinalDelete.php?id=32
    if($_GET) {

        // checking all required field
        foreach ($expected as $field) {           
            $value = trim($_GET[$field]);

            if(isNotEmpty($value)) {
                ${$field} = htmlentities($value, ENT_COMPAT, 'UTF-8');
                // validate field type
                if($message = typePatternCheck($field, $value)) {
                    $validationMsg[$field] = errMsg($message);
                }
                $submittedData[$field] = $value;       
            } 
        }

        $deleteGroup = query('DELETE from venue WHERE id = :id', array('id' => $submittedData['id']));

        if($deleteGroup) {
            header('Location: venueList.php?delete_success');
        }
        
    }
 ?>

 <main id="main-content">
        
        <?php 
        if(!empty($validationMsg)) { 
            echo '<h1>Oops something happen!</h1>';            
            echo output(@$validationMsg['id']);
            echo '<h3>We cannot process your request please click <a href="venueList.php">here</a> to go back to previous menu</h3>';
        } 
        ?>

    </main>

<?php 
    
    include '../inc/loggedIn_footer.php';

 ?>