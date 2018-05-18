<?php 
    
    function isRequired($field) {
        $required = array(
                    'id',
                    'gala_id',
                    'group_name',
                    'category_name',
                    'heatfinal_desc',
                    'date',
                    'event_name',
                    'venue_name',
                    'line_number',
                    'swimmer_name',
                    'recordtime',
                    'finish_number',
                    'email',
                    'parent_name',
                    'phone',
                    'address',
                    'postcode',
                    'username',
                    'password',
                    'fname',
                    'lname',
                    'dob',
                    'active');
        return in_array($field, $required);
    }

    function isGroupExists($group_name) {
        $result = query('SELECT name FROM groups WHERE name = :name', 
                        array('name' => $group_name)
                        );
        return ($result) ? 1 : 0;
    }

    function isCategoryExists($category_name) {
        $result = query('SELECT name FROM categories WHERE name = :name', 
                        array('name' => $category_name)
                        );
        return ($result) ? 1 : 0;
    }

    function isHeatFinalExists($heatfinal_desc) {
        $result = query('SELECT description FROM heat_final WHERE description = :description', 
                        array('description' => $heatfinal_desc)
                        );
        return ($result) ? 1 : 0;
    }

    function isEventExists($event_name) {
        $result = query('SELECT event_name FROM events WHERE event_name = :event_name', 
                        array('event_name' => $event_name)
                        );
        return ($result) ? 1 : 0;
    }

    function isVenueExists($venue_name) {
        $result = query('SELECT name FROM venue WHERE name = :name', 
                        array('name' => $venue_name)
                        );
        return ($result) ? 1 : 0;
    }

    function isSwimmerExists($swimmer_name) {
        $result = query('SELECT * FROM swimmers WHERE UPPER(CONCAT(lname,", ",fname)) LIKE :swimmer_name', 
                        array('swimmer_name' => '%'.$swimmer_name.'%')
                        );
        return ($result) ? 1 : 0;
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
            case 'id':
                 // post code only accept letters, numbers, and space 
                if (preg_match('/[^0-9]/i', $value) == true) {
                    $message = "Unindentified id!";
                }
                break;
            case 'gala_id':
                 // post code only accept letters, numbers, and space 
                if (preg_match('/[^0-9]/i', $value) == true) {
                    $message = "Unindentified gala id!";
                }
                break;
            case 'group_name':
                 // post code only accept letters, numbers, and space 
                if (preg_match('/[^\/a-zA-Z0-9\s]/i', $value) == true) {
                    $message = "Only letters, slash, and numbers!";
                }
                break;
            case 'category_name':
                 // post code only accept letters, numbers, and space 
                if (preg_match('/[^\/a-zA-Z0-9\s]/i', $value) == true) {
                    $message = "Only letters, slash, and numbers!";
                }
                break;
            case 'heatfinal_desc':
                 // post code only accept letters, numbers, and space 
                if (preg_match('/[^a-zA-Z0-9\s]/i', $value) == true) {
                    $message = "Only letters and numbers!";
                }
                break;
            case 'event_name':
                 // post code only accept letters, numbers, and space 
                if (preg_match('/[^\.a-zA-Z0-9\s]/i', $value) == true) {
                    $message = "Only letters, numbers, and period!";
                }
                break;
            case 'venue_name':
                 // post code only accept letters, numbers, and space 
                if (preg_match('/[^\.a-zA-Z0-9\s\-]/i', $value) == true) {
                    $message = "Only letters, numbers, dash & period!";
                }
                break;
            case 'gala_id':
                if (preg_match('/[^0-9]/i', $value) == true) {
                    $message = "Unindentified gala id!";
                }
                break;
            case 'line_number':
                if (preg_match('/[^0-9]/i', $value) == true) {
                    $message = "Unindentified line number!";
                }
                break;
            case 'swimmer_name':
                 // post code only accept letters, numbers, and space 
                if (preg_match('/[^a-zA-Z\,\s]/i', $value) == true) {
                    $message = "Unindentified swimmer!";
                }
                break;
            case 'recordtime':
                 // post code only accept letters, numbers, and space 
                if (preg_match('/[^a-zA-Z0-9\:\.\s]/i', $value) == true) {
                    $message = "Unindentified recordtime!";
                }
                break;
            case 'finish_number':
                if (preg_match('/[^0-9]/i', $value) == true) {
                    $message = "Unindentified line number!";
                }
                break;
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
            // Parent Management
            case 'email':
                // validate the email
                 if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
                    $message = "Email not valid!";  
                }
                break;
            case 'parent_name':
                // parent_name pattern alphabeth only
                if (preg_match('/[^a-zA-Z\s\.]/i', $value) == true) {
                    $message = "Last name only accept alphabet!";
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
            case 'group_name':
                if (strlen($value) < 5 || strlen($value) > 255){
                    $message = "Group min. 5 chars and max. 255 chars";
                }
                break;
            case 'category_name':
                if (strlen($value) < 5 || strlen($value) > 255){
                    $message = "Category min. 5 chars and max. 255 chars";
                }
                break;
            case 'heatfinal_desc':
                if (strlen($value) < 5 || strlen($value) > 255){
                    $message = "Description min. 5 chars and max. 255 chars";
                }
                break;
            case 'event_name':
                if (strlen($value) < 5 || strlen($value) > 255){
                    $message = "Event min. 5 chars and max. 255 chars";
                }
                break;
            case 'venue_name':
                if (strlen($value) < 5 || strlen($value) > 255){
                    $message = "Venue min. 5 chars and max. 255 chars";
                }
                break;
            case 'line_number':
                if ($value < 1 || $value > 5){
                    $message = "Line number 1-5";
                }
                break;
            case 'finish_number':
                if ($value < 0 || $value > 5){
                    $message = "Finish number 0-5";
                }
                break;
            // changePassword
            case 'password':
                if(strlen($value) < 4 || strlen($value) > 25) {
                    $message = "Password must be between 4-25 characters!";
                }    
                break;
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
            case 'parent_name':
                if(strlen($value) < 3 || strlen($value) > 40){
                    $message = "Parent name min. 3 chars and max length is 40!";
                }
                break;  
            }

        return $message;
    }

    function checkAvailability($field, $value) {
        $message = '';
        switch ($field) {
            case 'group_name':
                if(isGroupExists($value)){
                    $message = "Group already exists please input other name!";
                }
                break;
            case 'category_name':
                if(isCategoryExists($value)){
                    $message = "Category already exists please input other name!";
                }
                break;
            case 'heatfinal_desc':
                if(isHeatFinalExists($value)){
                    $message = "Heat/Final already exists please input other name!";
                }
                break;
            case 'event_name':
                if(isEventExists($value)){
                    $message = "Event already exists please input other name!";
                }
                break;
            case 'venue_name':
                if(isVenueExists($value)){
                    $message = "Venue already exists please input other name!";
                }
                break;
            // record time
            case 'swimmer_name':
                if(isSwimmerExists($value) == 0){
                    $message = "Unknown Swimmer";
                }
                break;
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