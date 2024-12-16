<?php

class Database
{
    private $con;

    // Construct
    function __construct()
    {
        $this->con = $this->connect();
    }

    // Connect to db
    private function connect()
    {
        $string = "mysql:host=" . DBHOST . ";dbname=" . DBNAME;

        try {
            $connection = new PDO($string, DBUSER, DBPASS);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connection;
        } catch (PDOException $e) {
            $info = (object)[];
            $info->message = "Database connection failed: " . $e->getMessage();
            $info->data_type = "error";
            echo json_encode($info);
            die();
        }
    }

    // Write to database
    public function write($query, $data_array = [])
    {
        $con = $this->connect();
        $statement = $con->prepare($query);
        $check = $statement->execute($data_array);

        if ($check) {
            return true;
        }

        return false;
    }

    // Read from database
    public function read($query, $data_array = [])
    {
        $con = $this->connect();
        $statement = $con->prepare($query);
        $check = $statement->execute($data_array);

        if ($check) {
            $result = $statement->fetchAll(PDO::FETCH_OBJ);
            if (is_array($result) && count($result) > 0) {
                return $result;
            }
            return false;
        }

        return false;
    }

    // Get user by ID
    public function get_user($userid)
    {
        $con = $this->connect();
        $arr['userid'] = $userid;
        $query = "SELECT * FROM users WHERE userid = :userid LIMIT 1";
        $statement = $con->prepare($query);
        $check = $statement->execute($arr);

        if ($check) {
            $result = $statement->fetchAll(PDO::FETCH_OBJ);
            if (is_array($result) && count($result) > 0) {
                return $result[0];
            }
            return false;
        }

        return false;
    }

    // Generate a random ID
    public function generate_id($max)
    {
        $rand = "";
        $rand_count = rand(4, $max);
        for ($i = 0; $i < $rand_count; $i++) {
            $r = rand(0, 9);
            $rand .= $r;
        }

        return $rand;
    }
}
