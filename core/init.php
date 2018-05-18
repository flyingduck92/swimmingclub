<?php 
    session_start();

    include 'DB/DB.php';
    include 'function/general.php';

    // if loggedIn
    $userData;
    if(loggedIn() === true) {
        $username_from_session = $_SESSION['username'];
        $roleId_from_session = $_SESSION['role_id'];
        
        // getUserData from general.php
        $userData = getUserData($username_from_session, $roleId_from_session);
    }

    $errors = array();
 ?>