<?php 
// Set proper content type header if not already set in api.php
header('Content-Type: application/json');

$info = (Object)[];
$data = []; // Initialize as array instead of false
$Error = ""; // Initialize Error variable

//validate info
if(empty($DATA_OBJ->email)) {
    $Error = "please enter a valid email";
}

if(empty($DATA_OBJ->password)) {
    $Error = "please enter a valid password";
}

if($Error == "") {
    // Only set email after validation
    $data['email'] = $DATA_OBJ->email;
    
    $query = "select * from users where email = :email limit 1";
    $result = $DB->read($query,$data);

    if(is_array($result)) {
        $result = $result[0];
        if($result->password == $DATA_OBJ->password) {
            $_SESSION['userid'] = $result->userid;
            $info->message = "You're successfully logged in";
            $info->data_type = "info";
            echo json_encode($info);
        } else {
            $info->message = "Wrong password";
            $info->data_type = "error";
            echo json_encode($info);
        }
    } else {
        $info->message = "Wrong email";
        $info->data_type = "error";
        echo json_encode($info);
    }
} else {
    $info->message = $Error;
    $info->data_type = "error";
    echo json_encode($info);
}