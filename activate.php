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

    $header = 'Activation';
    $title = 'Activation Page';

    // template
    include './inc/header.php'; //header
    include './inc/nav.php'; // navigation
    $validationMsg = array();
    $expected = array('email', 'username');

    /*check username and email for user activation on activatePageFunc*/
    include 'core/function/activatePageFunc.php';

    // activate.php?email=".$submittedData['email']."&username=".$submittedData['username']."
    // activate.php?email=sektiwicaksono92@gmail.com&username=test123
    if(isset($_GET['email'], $_GET['username']) === true) {

        foreach($expected as $field) {
            $value = trim($_GET[$field]);
            ${$field} = htmlentities($value, ENT_COMPAT, 'UTF-8');

            if($message = checkAvailability($field, $value)) {
                $validationMsg['form'] = errMsg($message);
            }
        }

        // if empty validation
        if(empty($validationMsg['form'])) {
            $username = htmlentities(trim($_GET['username']));
            $email = htmlentities(trim($_GET['email']));

            $activateSwimmer = query('UPDATE swimmers set active = 1 WHERE username=:username', array('username' => $username));
            $activateParent = query('UPDATE parents set active = 1 WHERE email=:email', array('email' => $email));

            if(($activateSwimmer && $activateSwimmer) == 1) {
                // if success redirect to success
                header('location: activate.php?success');

            } else {
                header('location: activate.php?failed');
            }
        }

    } else {
        $validationMsg['form'] = errMsg('We do not recognised your requests. Have you registered yet?');
    }

 ?>

     <main class="box main">

        <!-- Content -->
        <div class="NotFound">
            <img class="dolphin" src="./assets/pics/dolphin.png" alt="dolphin">
            <?php
                if(isset($_GET['success']) && empty($_GET['success'])) {
                    $validationMsg['form'] = successMsg('User activated');
                    echo "<h4>".output(@$validationMsg['form'])."</h4>";
                }
                elseif(isset($_GET['failed']) && empty($_GET['failed'])) {
                    $validationMsg['form'] = errMsg('Something happen with our system. Please try again later!');
                    echo "<h4>".output(@$validationMsg['form'])."</h4>";

                // show error here
                } else if($validationMsg['form']) {
                    echo "<h4>".output(@$validationMsg['form'])."</h4>";
                }
             ?>
            <h4>If you got an issue, please contact our admin for further information</h4>
        </div>

    </main>

<?php
    // template footer
    include './inc/footer.php';
?>