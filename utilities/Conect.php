<?php

 class Conect{
    private static $servername;
    private static $username;
    private static $password;
    private static $database;


function getCon(){
    self::$servername = getenv('DB_SERVERNAME');
    self::$username = getenv('DB_USERNAME');
    self::$password = getenv('DB_PASSWORD');
    self::$database = getenv('DB_DATABASE');

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        // Create a connection
        $conn = new mysqli(self::$servername, self::$username, self::$password, self::$database);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;   
}

function doSelect($statement,$conexion){

$rs =  $conexion->query($statement);


return $rs;

}


    // Close the connection
    function closeCon($conn){

        $conn->close();

    }

}

