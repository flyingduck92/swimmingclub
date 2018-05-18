<?php 

    if(connect() == false) {

?>
    <nav class="box nav">
        <div id="handle">Menu <span>&#9776;</span></div>
        <ul id="menu">
            <p style="color: black; pointer-events: none; margin-top: 10px">Sorry we have some problem occured. Please try again later</p>
        </ul>
    </nav>

<?php
   
    }  else { 

?>

    <nav class="box nav">
        <div id="handle">Menu <span>&#9776;</span></div>
        <ul id="menu">
            <a href="index.php"><li>Home</li></a>
            <a href="about.php"><li>About</li></a>
            <a href="contact.php"><li>Contact</li></a>
            <a href="login.php"><li>Login</li></a>
            <a href="register.php"><li>Register</li></a>
        </ul>
    </nav>

<?php
    
    }   
 
 ?>

