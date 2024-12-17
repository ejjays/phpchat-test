<?php 
$info = (Object)[];
$Error = ""; // Initialize Error variable

// Initialize $data as an array
$data = array();
$data['email'] = $DATA_OBJ->email;

// Validate info
if(empty($DATA_OBJ->email)) {
    $Error = "please enter a valid email";
}

if(empty($DATA_OBJ->password)) {
    $Error = "please enter a valid password";
}

// Add proper JSON response handling
if($Error == "") {
    $query = "select * from users where email = :email limit 1";
    $result = $DB->read($query, $data);

    if(is_array($result)) {
        $result = $result[0];
        if($result->password == $DATA_OBJ->password) {
            $_SESSION['userid'] = $result->userid;
            $info->success = true;
            $info->message = "You're successfully logged in";
            $info->data_type = "info";
        } else {
            $info->success = false;
            $info->message = "Wrong password";
            $info->data_type = "error";
        }
    } else {
        $info->success = false;
        $info->message = "Wrong email";
        $info->data_type = "error";
    }
} else {
    $info->success = false;
    $info->message = $Error;
    $info->data_type = "error";
}

// Send JSON response
echo json_encode($info);
die;