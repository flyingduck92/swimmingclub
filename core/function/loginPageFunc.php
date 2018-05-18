<?php 

    function isRequired($field) {
        $required = array('username','password');
        return in_array($field, $required);
    }

 ?>