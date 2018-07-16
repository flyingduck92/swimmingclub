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

    // check connection
    if(connect() == false) {
        header('Location: index.php');
    }

    $header = 'Staffordshire Swimming Club';
    $title = 'About Page';

    // template
    include './inc/header.php'; //header
    include './inc/nav.php'; // navigation

?>

    <main class="box main about-main">

        <h2 id="about"><?= $title; ?></h2>
        <img class="front-pic" src="./assets/pics/competitive_swimmer.jpg">
        <br>
        <h3>Staffordshire Swimming Club</h3>

        <p>Staffordshire Swimming Club (SSC) was organised in 2006 to meet the demands for more swimming opportunities once children had outgrown the successful Swim School programme at Staffordshire Pools, Stoke-on-Trent. In ten short years, we have gone from strength to strength. Today, we have a comprehensive competitive program that caters to all swimmers - from pre-competitive and development, to county, regional, national and masters level.</p>

        <p> The history of SSC is not long. The club grew from ten families gathering to request a few extra swimming sessions after their children had graduated to Stage 8 of the Swim England Learn to Swim Programme. In 2006, two sessions were added and the Sub Zero Squad was formed. By the end of that year, the club had grown to thirty children in two squads, training four days a week. Over the next couple of years the club reorganised, changed its name to Staffordshire Bees Swimming Club and by February 2009 was an affiliate club competing within Middlesex County. In 2011, Staffordshire Bees changed its name to Staffordshire. Today, Staffordshire Swimming Club comprises nearly 150 swimmers across 6 vibrant squads. </p>

        <p>If you are interested in joining, or for additional information about SSC, please see <a href="register">Joining SSC</a></p>

        <h3>Club aims</h3>

        <p>Staffordshire Swimming Club aims to provide an enjoyable, healthy, supportive learning environment where champion swimmers can thrive. The club actively encourages swimmers of all ages and abilities to take part in competitions to at least the county or county development level. We strive to nurture and support champion swimmers all the while giving every swimmer a chance to enjoy the many aspects of competition and its rewards.</p>

        <h3>Highlights</h3>

        <p> British Summer Nationals 2017 - Qualifiers were David Bloomfield, Spencer Williams, Alesha Kelly, Zoe Beaulieu, Sloane Carroll and Felicite de Buchet. Alesha Kelly won Bronze in 200m Butterfly and David Bloomfield won Bronze in 50m Breaststroke. </p>

        <p>
            Swim England Summer National Championships 2017 - Qualifiers were David Bloomfield, Spencer Williams, Alesha Kelly, Emily Ellis, Taylor Williams, Sophia Ground and George Eldredge. <br>
            ETU Duathlon European Championships 2017 - Staffordshire Master Leah Walland wins Bronze for GB.
        </p>

    </main>

<?php
    // template footer
    include './inc/footer.php';

?>
