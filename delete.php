<?php
include 'config.php';
if($_GET['delete']){
    $delete=$_GET['delete'];
    $query="delete from students where id='$delete'";
    $result=$conn->query($query);
    if($result){
        header('location:index.php?msg=data are deleted successfully');
        exit();
    }else{
        header("location:index.php?msg= student record not deleted");
    }
}
