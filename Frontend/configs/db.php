<?php 
    $connect = mysqli_connect("localhost","root", "","ai_trade");

    if(!$connect) {
        die("Connection failed");
    }
?>