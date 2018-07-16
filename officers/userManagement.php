<?php
    ob_start();
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

        <a href="parentsList.php"><div class="box" style="background-color: #edae1c;">Parent List</div></a>
        <a href="swimmersList.php"><div class="box" style="background-color: #1c6eed;">Swimmers</div></a>
        <a href="officersList.php"><div class="box" style="background-color: #4e09ee;">Officer List</div></a>

    </main>

<?php

    include '../inc/loggedIn_footer.php';

 ?>