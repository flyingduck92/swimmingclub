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

    $header = 'Staffordshire Swimming Club';
    $title = 'Contact Page';

    // template
    include './inc/header.php'; //header
    include './inc/nav.php'; // navigation

?>

    <main class="box main">

        <h2><?= $title; ?></h2>
        <img class="front-pic" src="./assets/pics/coaching.jpeg">
        <br>
        <h2>For immediate assistance, &#9742; call us at 1-303-893-0552</h2>
        <p><b>We’re here 5 days a week, 9:00 a.m. to 5:00 p.m.</b></p>

        <b>Main Address</b>
        <address>
            College Road <br>
            University Quarter <br>
            Stoke-on-Trent <br>
            Staffordshire ST4 2DE
        </address><br>

        <p>Please use the phone number on this page to contact us or email us to
            <a href="mailto:sektiwicaksono92@gmail.com" target="_top">Staffordshire Swimming Club</a>
        </p>

    </main>

<?php
    // template footer
    include './inc/footer.php';

 ?>
