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

    
 ?>

    <main id="main-content">
        
        <h1>Welcome <?= $userData[0]->username ?></h1>

        <label>
            <b>First Name: <?= $userData[0]->fname ?></b>
        </label><br>
        <label>
            <b>Last Name: <?= $userData[0]->lname ?></b>
        </label><br>
        <label>
            <b>Date of Birth: <?= $userData[0]->dob ?></b>
        </label><br><br>
        <label>
            <a href="editBasicInfo.php">
                <button class="edit" style="padding: 5px;">Edit Basic Information</button>
            </a>
            <a href="changePassword.php">
                <button class="delete" style="padding: 5px;">Change Password</button>
            </a>
        </label>


    </main>

<?php 
    
    include '../inc/loggedIn_footer.php';

 ?>