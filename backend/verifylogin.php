<?php
$clientId = $_COOKIE['client_id'];
$sessionId = $_COOKIE['session_id'];

$reponseObject = [];

function deletePreviousCookie(){
    setcookie('session_id', '', [
        'expires'=>time()-1000,
        'path'=> '/',
        'secure'=> true,
        'httponly' => true,
        'samesite'=>'strict'
    ]);
    setcookie('client_id', '', [
        'expires'=>time()-1000,
        'path'=> '/',
        'secure'=> true,
        'httponly' => true,
        'samesite'=>'strict'
    ]);
}
if(empty($clientId) || empty($sessionId)){
    deletePreviousCookie();
    exit(json_encode(['login'=> false]));
}

// Verifying cookies
// Connecting to the database
// Fetching sub root details
require_once $_SERVER['DOCUMENT_ROOT'].'/sub_root.php';
// require_once MAIN_ROOT.'/backend/database.php';

function verifyFounder(){
    // Including founder details
    require_once MAIN_ROOT.'/backend/founder_key.php';
    if(FOUNDER_ID != $clientId || FOUNDER_SESSION != $sessionId){
        deletePreviousCookie();
        exit(json_encode(['login'=> false]));       
    }
}

function verifyUser($userType, $database=null){
    if($userType == 'founder'){
        return verifyFounder();
    }
    $userTableNames = ['user'=>'users', 'admin'=>'admins']
    $userTableIdFieldName = ['user'=>'user_id', 'admin'=>'admin_id']
    $stmt= $database->prepare('SELECT * FROM `'.$userTableNames[$userType].'` WHERE `'.$userTableIdFieldName[$userType].'` = ?;');
    $stmt->bind_param('i', $clientId);
    $stmt->execute();
    $record=$stmt->get_result()->fetch_assoc();
    
    if(empty($record) || bin2hex($record['session_id']) != $sessionId){
        deletePreviousCookie();
        exit(json_encode(['login'=> false]));       
    }
}