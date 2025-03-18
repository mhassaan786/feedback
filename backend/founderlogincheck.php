<?php
// Fetching sub root details
require_once $_SERVER['DOCUMENT_ROOT'].'/sub_root.php';
require_once MAIN_ROOT.'/backend/verifylogin.php';

// Checking login status
verifyUser('founder');

// Sending success response
echo json_encode(['login'=>true]);