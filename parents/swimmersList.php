<?php
    include '../core/init.php';

    if (loggedIn() && $_SESSION['role_id'] == 1) {
        header('Location: ../officers/index.php');
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
    include '../core/function/dataPaginate.php';
    
 ?>

    <main id="main-content">

        <?php 
            if(isset($_GET['update_success']) && empty($_GET['update_success'])) {
                    $validationMsg['form'] = successMsg('Swimmer Successfully Updated');
                    output(@$validationMsg['form']);
            } 
        ?>
        
        <h1>Swimmer List</h1>
        <br>

        <?php 

            $page = (isset($_GET['page'])) ? (int)$_GET['page']:1;

            // if page less than 1
            if($page < 1) {
                header('Location: '.basename($_SERVER['PHP_SELF']));
            }

            // setup limit items per page
            $limit=10;

            $results = getData('SELECT * FROM swimmers WHERE email=\''.$userData[0]->email.'\' ORDER BY lname', $limit, $page);

            // echo '<pre>';
            // print_r($results);
         ?>

        <table class="parent table-striped">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Date of Birth</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php 
                foreach ($results->data as $result) {
             ?>
                <tr>
                    <td><?= $result->id ?></td>
                    <td><?= $result->username ?></td>
                    <td><?= $result->fname ?></td>
                    <td><?= $result->lname ?></td>
                    <td><?= $result->dob ?></td>
                    <td><?= ($result->active)? 'Active':'Not Active' ?></td>
                    <td>
                    <a href="swimmersView.php?id=<?= $result->id; ?>"><button class="info">View</button></a> 
                    <a href="swimmersEdit.php?id=<?= $result->id; ?>"><button class="edit">Edit</button></a> 
                </td>
                </tr>                
            <?php        
                }
             ?>
        </table>

        <?php 
            echo '<br>';
            echo 'Total '.$results->total.'<br><br>';

            // pageLinks($links=3, $total, $limit, $page)
            // show links: show 1 prev link and 1 next link, from current page 
            $links=1; 
            echo $showLinks = pageLinks($links, $results->total, $results->limit, $results->page); 
         ?>

    </main>

<?php 
    
    include '../inc/loggedIn_footer.php';

 ?>