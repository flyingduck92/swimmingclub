<?php 

    function isNotEmpty($value) {
        return !empty($value) || $value === 0;
    }

    function errMsg($msg) {
        return '<span class="error">'.$msg.'</span>';
    }

    function successMsg($msg) {
        return '<span class="success">'.$msg.'</span>';
    }

    function output($value){
        echo $value = ($value) ? $value : '';
    }
    
    // SEND MAIL USING PHP MAIL WITH GMAIL AS MAIL SERVER
    function send_email($to, $subject, $body) {

        // mailserver host check 
        $mailserver;
        if($_SERVER['SERVER_NAME'] == 'localhost') {
            // localhost
            $mailserver='sektiwicaksono92@gmail.com';
            
        } else {
            // gwiddle host
            $mailserver='ssc@sekti92.gwiddle.co.uk';
        }

        // Headers
        $headers = "From: Staffordshire Swimming Club <".$mailserver.">\r\n";
        // Additional Header
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/plain;charset=UTF-8\r\n";

        return $result = mail($to, $subject, $body, $headers);
    }

    function activate($email, $username) {
        $email    = htmlentities(trim($email), ENT_COMPAT, 'UTF-8');
        $email    = htmlentities(trim($username), ENT_COMPAT, 'UTF-8');

        $activateSwimmers = query('UPDATE swimmers SET active = 1 WHERE username = :username', array('username' => $username));
        $activateSwimmers = query('UPDATE parents SET active = 1 WHERE username = :email', array('email' => $email));

        // return $result = ($activateSwimmers && $activateSwimmers) ? true : false;            
    }

    // calculate age
    // Subtract from 1970 because strtotime calculates time from 1970
    function getAge($date) {
        return intval(date('Y', time()-strtotime($date))) - 1970;
    }

    // LOGIN CHECK
    function loggedIn() {
        return (isset($_SESSION['username'])) ? true : false;
    }

    // Get all swimmerData using 
    function getUserData($username, $roleId) {
        $userData = '';
        
        if($roleId == 1) {
            $userData = query('SELECT * FROM officers WHERE username = :username', array('username' => $username));
        }

        if($roleId == 2) {
            $userData = query('SELECT * FROM parents WHERE email = :email', array('email' => $username));
        }

        if($roleId == 3) { 
            $userData = query('SELECT s.id, s.role_id, s.username, s.fname, s.lname, s.dob, s.email, p.parent_name, p.phone, p.address, p.postcode  
                               FROM swimmers s JOIN parents p ON s.email = p.email WHERE username = :username', array('username' => $username));
        }

        return $userData;
    }

 ?>