<?php 
    
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
    
    $header = 'Staffordshire Swimming Club';
    $title = 'Welcome to Staffordshire Swimming Club';

    // template
    include './inc/header.php'; //header
    include './inc/nav.php'; // navigation

?>

    <main class="box main">
        
        <h2><?= $title; ?></h2>
        <img class="front-pic" src="./assets/pics/swimming-wallpapers.jpg">
        <br>
        <p>
            Staffordshire Swimming Club (SSC) was organised in 2006 to meet the demands for more swimming opportunities once children had outgrown the successful Swim School programme at Staffordshire Pools, Stoke-on-Trent. In ten short years, we have gone from strength to strength. Today, we have a comprehensive competitive program that caters to all swimmers - from pre-competitive and development, to county, regional, national and masters level.
        </p>
        <p>
            SSC competes in the National Arena League in Division 1 and in 2017 boasted 10 swimmers in the English and British National Championships, with more than 20 qualifying for London Regionals. Our year began with 45 swimmers qualifying for the Middlesex Youth and Age-Group County Championships.
        </p>
        <p>If you are interested in joining, or for additional information about SSC, please see <a href="register.php">Joining SSC</a></p>
    </main>

<?php 

    // template footer
    include './inc/footer.php';

 ?>    