<?php
    $servername = '202.28.34.197';
    $username = 'web65_64011212085';
    $password='64011212085@csmsu';
    $dbname='web65_64011212085';

    $dbconn=new mysqli($servername,$username,$password,$dbname);
    if($dbconn->connect_error){
        die("Error".$con->connect_error);
    }
?>