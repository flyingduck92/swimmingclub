<?php 
    
    // Development - Localhost
    $host;
    $dbName;
    $dbusername;
    $dbpassword;
    
    // put pdo variable as global to handle last inserted id
    $pdo;

    // if localhost 
    if($_SERVER['SERVER_NAME'] == 'localhost') {
        $host = "localhost";
        $dbName = "pdocourse";
        $dbusername = "root";
        $dbpassword = "";

    // gwiddle
    } else {
        $host = "localhost";
        $dbName = "sekti92_pdocourse";
        $dbusername = "crimson";
        $dbpassword = "crimson";
    }

    /**
     * Handle Connnection
     */
    function connect() {
        // connection template
        // $pdo = new PDO('mysql:host=127.0.0.1;dbname=SocialNetwork;charset=utf8','root','');
        
        global $host;
        global $dbName;
        global $dbusername;
        global $dbpassword;
        global $pdo;
        
        try {
            $pdo = new PDO("mysql:host=".$host.";dbname=".$dbName."", $dbusername, $dbpassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);     
            // $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // fetch as associative 
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); // fetch as object
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            return $pdo;
        
        } catch (PDOException $e) {
            // die('error  message: '.$e->message().'<br>');
            // die('Our system have some problems now. Please try again later');
            return false;
        }
    }

    /**
     * Handle Query
     *
     * Strictly speaking, there's actually no escaping needed, because the parameter value is never interpolated into the query string.

       The way query parameters work is that the query is sent to the database server when you called prepare(), and parameter values are sent later, when you called execute(). So they are kept separate from the textual form of the query. 
       There's never an opportunity for SQL injection (provided  PDO::ATTR_EMULATE_PREPARES is false).

       So yes, query parameters help you to avoid that form of security vulnerability.
     * Source: https://stackoverflow.com/questions/1314521/how-safe-are-pdo-prepared-statements
     * Source: https://stackoverflow.com/questions/60174/how-can-i-prevent-sql-injection-in-php
     */
    function query($query, $params = array()) {
        
        $stmt = connect()->prepare($query);
        $stmt->execute($params);

        // if select return data
        if(explode(' ', $query)[0] == 'SELECT'){
            $data = $stmt->fetchAll();
            return $data;

        // otherwise (update, delete, insert) return status 1 for success or 0 for failed
        } else {
            return ($stmt) ? true : false;
        }
    }

    // wrap the last inserted id to function
    function lastInsertedId() {
        return $pdo->lastInsertId();
    } 

    // $data= query('SELECT * FROM users WHERE username=:username', array('username'=>'rroyson7'));
    // foreach ($data as $result) {
    //     echo $result['id'].'<br>';
    //     echo $result['username'].'<br>';
    //     echo $result['password'].'<br>';
    //     echo $result['email'];
    // }
    // 
    // QUERY ALL WITH/WITHOUT LIMIT
    // $data = query('SELECT * FROM posts LIMIT :limit', array('limit'=>2));
    // $data = query('SELECT * FROM posts');

    // foreach ($data as $item) {
    //     echo $item->id.'<br>';
    //     echo $item->title.'<br>';
    //     echo $item->body.'<br>';
    //     echo $item->author.'<br><br>';
    // }

 ?>