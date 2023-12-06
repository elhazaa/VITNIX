<?php
    // Initialize the session
    session_start();

    require_once "config.php";
    
    // Redirect to login page
    //header("location: login.php");

    // Prepare an insert statement
    $param_visit_id = $_GET["id"];

    

    if ($link->connect_error) {
     die("Connection failed: " . $link->connect_error);
    }

    // sql to delete a record
    $sql = "DELETE FROM visits WHERE visit_id = $param_visit_id ";

    if ($link->query($sql) === TRUE) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        echo "Error deleting record: " . $link->error;
    }
    
exit;
?>