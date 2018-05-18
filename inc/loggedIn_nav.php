</head>
<body id="body" class="menu">   
<header>
    <a href="#" class="nav-toggle" id="nav-toggle">☰ Menu</a>
    <nav class="nav-menu">
        <h1>Staffordshire Swimmming Club</h1>
        <legend></legend>
        <ul>
            <li style="margin-top: 5px; font-size: 12pt;">
                <a href="index.php"><?= $_SESSION['username']; ?></a>
            </li>
            <li style="margin-top: 5px;margin-bottom: 5px;">
                <a href="logout.php">Log Out</a>
            </li>
        </ul>
        <legend></legend>
        <?php 
            if($_SESSION['role_id'] == 1) {

                echo '                    
                    <!-- CRUD Gala (event, gala, record, etc) -->
                    <h4><a href="galaManagements.php">Gala\'s Management</a></h4>

                    <!-- CRUD Users -->
                    <h4><a href="userManagement.php">User\'s Management</a></h4>
                ';

            }
            if($_SESSION['role_id'] == 2) {

                echo '
                    <!-- Read operation gala result order by date desc-->
                    <h4><a href="galaResult.php">Gala\'s Result</a></h4>

                     <!-- View & Update Operation on Swimmer Password-->
                    <h4><a href="swimmersList.php">Swimmer\'s List</a></h4>
                ';

            }
            if($_SESSION['role_id'] == 3) {

                echo '
                    <!-- Read operation gala result order by date desc-->
                    <h4><a href="galaResult.php">Gala\'s Result</a></h4>

                    <!-- Read operation myResult order by date desc-->
                    <h4><a href="myResult.php">My result</a></h4>
                ';

            }
         ?>
    </nav>
</header>