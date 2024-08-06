<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        h4{
            text-align: center;
        }
        header a{
            display: inline;
            letter-spacing: 0.2cap;
        }
        table {
            width: auto 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {background-color: #ddd;}
        a {
            color: blue;
            text-decoration: none;
        }
        a:hover {
            color: red;
        }
        .error{
            color: aquamarine;
        }
    </style>
</head>
<body>
    
<?php
session_start();
if(!isset($_SESSION['username'])){
    header('location:login.php');
    exit();
}else{
    $username = $_SESSION['username'];
}
?>
<header>
    <h4>STUDENTS MANAGEMENT INFORMATION SYSTEM</h4>
    <a href="logout.php">logout</a>
    <a href=""><div><h3>welcome <?php echo strtoupper($username);?> To Student Management System</h3></div><br></a>
</header>
<?php
include 'config.php'; 
if(isset($_GET['msg'])){
    $msg=$_GET['msg'];
}
else{
    $msg="";
}
?>
<div class="message">
    <p><?php echo htmlspecialchars($msg) ;?></p>
    <a href="addStudent.php"><button>add new students</button></a>
</div>
<?php
$query="select * from students ";
$result = mysqli_query($conn, $query);


if(mysqli_num_rows($result) > 0){
    echo "<table border='1'>";
    echo "<tr>
              <th>ID</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Date of Birth</th>
              <th>Gender</th>
              <th>Email</th>
              <th>Phone Number</th>
              <th>Registration Number</th>
              <th>Registered time</th>
              <th colspan='2'>Actions</th>
        </tr>";
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>". $row["id"]. "</td>
                  <td>". $row["fname"]. "</td>
                  <td>". $row["lname"]. "</td>
                  <td>". $row["dob"]. "</td>
                  <td>". $row["gender"]. "</td>
                  <td>". $row["email"]. "</td>
                  <td>". $row["phone"]. "</td>
                  <td>". $row["reg_number"]. "</td>
                  <td>". $row["created_at"]. "</td>
                  <td><a href='edit.php?edit=".$row['id']."'>Edit</a></td>
                  <td><a href='delete.php?delete=".$row['id']."'>delete</a></td>
                  </tr>";
    }
    echo "</table>";
}
else{
    echo"<div class='error'>data not found</div>";
}

?>
</body>
</html>