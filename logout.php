<?php 
session_start();
include 'config.php';
if(isset($_SESSION['username'])){
    session_unset();
    session_destroy();
    header("location:login.php");
    exit();
}
