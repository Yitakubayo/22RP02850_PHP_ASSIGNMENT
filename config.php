<?php
$conn=new mysqli('localhost','root','','studentsManagementSystem');
if(!$conn){
    die("Connection failed: ").$conn->connect_error;
}