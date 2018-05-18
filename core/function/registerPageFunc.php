<?php 

    /* VALIDATION FUNCTION 
     * Applied in: Register
     */ 
    function isRequired($field) {
        $required = array('username', 'fname', 'lname', 'dob', 'email', 'parentName', 'phone', 'address', 'postcode', 'password','password2');
        return in_array($field, $required);
    }

    function isUsernameExists($username) {
        $result = query('SELECT username FROM swimmers WHERE username = :username 
                        UNION 
                        SELECT username FROM officers WHERE username = :username2', 
                        array(
                            'username' => $username,
                            'username2' => $username)
                        );
        return ($result) ? 1 : 0;
    }

    function isEmailExists($email) {
        $result = query('SELECT email FROM parents WHERE email = :email', 
                        array('email' => $email)
                        );
        return ($result) ? 1 : 0;
    }

    function typePatternCheck($field, $value) {
        $message = '';
        switch ($field) {
            case 'username':
                // username only accept letters, numbers, underscore and dot
                if (preg_match('/[^a-zA-Z0-9\_\.]/i', $value) == true) {
                    $message = "Username only accept alphabets, numbers, underscore, and dot!";
                }
                break;
            case 'fname':
                // fname pattern alphabeth only
                if (preg_match('/[^a-zA-Z\s\.]/i', $value) == true) {
                    $message = "First name only accept alphabet!";
                }
                break;
            case 'lname':
                // lname pattern alphabeth only
                if (preg_match('/[^a-zA-Z\s\.]/i', $value) == true) {
                    $message = "Last name only accept alphabet!";
                }
                break;
            case 'email':
                // validate the email
                 if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
                    $message = "Email not valid!";  
                }
                break;
            case 'parentName':
                // parent name pattern
                if (preg_match('/[^a-zA-Z\s\.]/i', $value) == true) {
                    $message = "Parent name only accept alphabet!";
                }
                break;
            case 'phone':
                // phone regex
                if (preg_match('/[^\0-9]/i', $value) == true) {
                    $message = "Phone number not valid!";
                }
                break;
            case 'address':
                // address only accept letters, space, numbers, dash and dot
                if (preg_match('/[^a-zA-Z0-9\s\-\.]/i', $value) == true) {
                    $message = "Address only accept alphabets, space, numbers, dash, and dot!";
                }
                break;
            case 'postcode':
                 // post code only accept letters, numbers, and space 
                if (preg_match('/[^a-zA-Z0-9\s]/i', $value) == true) {
                    $message = "Postcode only accept alphabets and numbers!";
                }
                break;
        }
        return $message;
    }

    function validateLength($field, $value) {
        $message = '';
        switch ($field) {
            case 'username':
                if (strlen($value) < 5 || strlen($value) > 20){
                    $message = "Username min. 5 chars and max. 20 chars";
                }
                break;
            case 'fname':
                if(strlen($value) < 3 || strlen($value) > 20) {
                    $message = "First name min. 3 chars and max length is 20!";
                }
                break;
            case 'lname':
                if(strlen($value) < 3 || strlen($value) > 50) {
                    $message = "Last name min. 3 chars and max length is 50!";
                }
                break;
            case 'parentName':
                if(strlen($value) < 3 || strlen($value) > 40){
                    $message = "Parent name min. 3 chars and max length is 40!";
                }
                break;    
            case 'password':
                if(strlen($value) < 4 || strlen($value) > 25) {
                    $message = "Password must be between 4-25 characters!";
                }    
                break;
            }
        return $message;
    }

    function checkAvailability($field, $value) {
        $message = '';
        switch ($field) {
            case 'username':
                if(isUsernameExists($value)){
                    $message = "Username already exists!";
                }
                break;
            case 'email':
                if(isEmailExists($value)){
                    $message = "Email already exists please choose other email address!";
                }
                break;
            case 'dob':
                $ageCalc = getAge($value);
                if($ageCalc < 5){
                    $message = "Minimum age should be 5 years old";
                }
                break;
        }
        return $message;
    }

 ?>