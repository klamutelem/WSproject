<?php

    $db_server = 'localhost';
    $db_user = 'root';
    $db_pass = '';
    $db_name = 'businessdb';
    $conn = '';
    $databseconnectivity = false;

    try{
        $conn = mysqli_connect($db_server,
                               $db_user,    
                               $db_pass,  
                               $db_name);
        /*mysqli_connect function is for connecting to a DB . In this case we create an object variable which is 
        representing our database connection. This will allows us to use this variable in code
        */
        $databseconnectivity = true;
    }
    catch (mysqli_sql_exception){
        $databseconnectivity = false;
        
    }
    
?>