<?php
$userEmail = trim($_POST['email']);
$userPassword = trim($_POST['pass']);

// Checking for information validity
if(empty($userEmail) || empty($userPassword))
    die('Invalid request');

// Including main root
require_once $_SERVER['DOCUMENT_ROOT']. '/sub_root.php';
// Including founder's details
require_once MAIN_ROOT.'/backend/founder_key.php';

// Checking if founder logged in ?
if($userEmail == FOUNDER_EMAIL && $userPassword == FOUNDER_KEY){

    // Setting cookie for login
    setcookie('session_id', FOUNDER_SESSION, [
        'expires'=>time()+5184000,
        'path'=> '/',
        'secure'=> true,
        'httponly' => true,
        'samesite'=>'strict'
    ]);
    setcookie('client_id', FOUNDER_ID, [
        'expires'=>time()+5184000,
        'path'=> '/',
        'secure'=> true,
        'httponly' => true,
        'samesite'=>'strict'
    ]);


    echo 'founder_logged_in';
    exit;
}

// For users
$stmt=$database->prepare('SELECT `user_id`, `pass_hash`, `session_id` FROM `users` WHERE `email` = ?;');
$stmt->bind_param('s', $userEmail);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows){
    $record = $result->fetch_assoc();
    if($record['pass_hash'] == hash('sha256', $userPassword)){
        // User logged in

        // Setting cookie for login
        setcookie('session_id', bin2hex($record['session_id']), [
            'expires'=>time()+5184000,
            'path'=> '/',
            'secure'=> true,
            'httponly' => true,
            'samesite'=>'strict'
        ]);
        setcookie('client_id', $record['user_id'], [
            'expires'=>time()+5184000,
            'path'=> '/',
            'secure'=> true,
            'httponly' => true,
            'samesite'=>'strict'
        ]);
    
        echo 'user_logged_in';
        exit;
    }
}
$stmt->close();

// Fetching admin data
// Connecting to the database
require_once MAIN_ROOT.'/backend/database.php';
$stmt=$database->prepare('SELECT `admin_id`, `pass_hash`, `session_id` FROM `admin` WHERE `email` = ?;');
$stmt->bind_param('s', $userEmail);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows){
    $record = $result->fetch_assoc();

    if($record['pass_hash'] == hash('sha256', $userPassword)){
        // User logged in

        // Setting cookie for login
        setcookie('session_id', bin2hex($record['session_id']), [
            'expires'=>time()+5184000,
            'path'=> '/',
            'secure'=> true,
            'httponly' => true,
            'samesite'=>'strict'
        ]);
        setcookie('admin_id', $record['admin_id'], [
            'expires'=>time()+5184000,
            'path'=> '/',
            'secure'=> true,
            'httponly' => true,
            'samesite'=>'strict'
        ]);
        echo 'admin_logged_in';
        exit;
    }
}
$stmt->close();

$database->close();

echo 'wrong_information';

