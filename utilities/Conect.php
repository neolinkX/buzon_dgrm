<?php

 class Conect{
    const servername ="localhost";
    const username = "root";
    const password = "";
    const database = "buzon_dgrm_1897654"; 

    
    /*
    const servername ="localhost";
    const username = "u180558178_nlx_1897654";
    const password = "ObladiOblada01$.";
    const database = "u180558178_buzon_dgrm_189";
    */

function getCon(){
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        // Create a connection
        $conn = new mysqli(self::servername, self::username, self::password, self::database);

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

