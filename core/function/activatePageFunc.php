<?php 
    
function checkAvailability($field, $value) {
    $message = '';
    switch ($field) {
        case 'email':
            $emailCheck = query('SELECT email FROM parents WHERE email=:email', array('email'=>$value));
            if(count($emailCheck) < 1) {
                $message = 'Email does not found. Have you registered yet?';
            }
            break;
        case 'username':
            $usernameCheck = query('SELECT username FROM swimmers WHERE username=:username', array('username'=>$value));
            if(count($usernameCheck) < 1) {
                $message = 'Username does not recognised. Have you registered yet?';
            }
            break;    
    }
    return $message;
}

 ?>