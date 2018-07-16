
<?php
    ob_start();
    include '../core/init.php';

    if (loggedIn() && $_SESSION['role_id'] == 1) {
        header('Location: ../officers/index.php');
        exit();

    } elseif (loggedIn() && $_SESSION['role_id'] == 2) {
        header('Location: ../parents/index.php');
        exit();

    } elseif (!loggedIn()) {
        header('Location: ../login.php');
        exit();
    }

    include '../inc/loggedIn_header.php';
    include '../inc/loggedIn_nav.php';

    // print_r($userData);

 ?>

    <main id="main-content">

        <h1>Welcome <?= $userData[0]->username ?></h1>

        <label>
            <b>Name: <?= $userData[0]->lname.', '.$userData[0]->fname ?></b>
        </label><br>
        <label>
            <b>Date of Birth: <?= $userData[0]->dob ?></b>
        </label><br>
        <label>
            <b>Parent Name: <?= $userData[0]->parent_name ?></b>
        </label><br>
        <label>
            <b>Phone: <?= $userData[0]->phone ?></b>
        </label><br>
        <label>
            <b>Address: <?= $userData[0]->address ?></b>
        </label><br>
        <label>
            <b>Postcode: <?= $userData[0]->postcode ?></b>
        </label><br>
        <br>
        <label>
            <a href="changePassword.php">
                <button class="delete" style="padding: 5px;">Change Password</button>
            </a>
        </label>

<?php

    include '../inc/loggedIn_footer.php';

 ?>