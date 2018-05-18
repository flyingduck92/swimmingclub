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
        
        <a href="galaList.php"><div class="box">Gala List</div></a>
        <a href="groupList.php"><div class="box">Group List</div></a>
        <a href="categoryList.php"><div class="box">Category List</div></a>
        <a href="heatFinalList.php"><div class="box">Heat/Final Management</div></a>
        <a href="eventsList.php"><div class="box">Event List</div></a>
        <a href="venueList.php"><div class="box">Venue List</div></a>

    </main>

<?php 
    
    include '../inc/loggedIn_footer.php';

 ?>