<?php
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
session_start();

if(session_status() !== PHP_SESSION_ACTIVE) {
    echo "Session not active";
    die();
}

$DATA_RAW = file_get_contents("php://input");
$DATA_OBJ = json_decode($DATA_RAW);

$info = (object)[];

//check if logged in
if(!isset($_SESSION['userid'])) {
    if(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type != "login" && $DATA_OBJ->data_type != "signup") {
        $info->logged_in = false;
        echo json_encode($info);
        die;    
    }
}

require_once("classes/autoload.php");
$DB = new Database();

$Error = "";

//process the data
if(isset($DATA_OBJ->data_type)) {
    switch($DATA_OBJ->data_type) {
        case "signup":
            include("includes/signup.php");
            break;
        case "login":
            include("includes/login.php");
            break;
        case "logout":
            include("includes/logout.php");
            break;
        case "user_info":
            include("includes/user_info.php");
            break;
        case "contacts":
            include("includes/contacts.php");
            break;
        case "chats":
        case "chats_refresh":
            include("includes/chats.php");
            break;
        case "settings":
            include("includes/settings.php");
            break;
        case "save_settings":
            include("includes/save_settings.php");
            break;
        case "send_message":
            include("includes/send_message.php");
            break;
        case "delete_message":
            include("includes/delete_message.php");
            break;
        case "delete_thread":
            include("includes/delete_thread.php");
            break;
    }
}