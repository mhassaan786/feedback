<?php
// Checking if logged in or not ?
// Including server address
require_once $_SERVER['DOCUMENT_ROOT'].'/sub_root.php';
require_once MAIN_ROOT.'/backend/database.php';

// Checking for user is logged in or not
require_once MAIN_ROOT.'/backend/verifylogin.php';
verifyUser('founder');

