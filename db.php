<?php
function getdb(){



    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "exports";


    // $servername = "127.0.0.1";
    // $username = "uq1xcmwyqogdj";
    // $password = "hbzbtbiu6vso";
    // $db = "dbf7texqu3kunx";

      $mysqli = new mysqli($servername, $username, $password, $db);
               // echo "Connected successfully"; 
      if ($mysqli -> connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
         exit();
    }
        return $mysqli;
    }