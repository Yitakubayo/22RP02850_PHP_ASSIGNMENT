<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <style>
        label {
            margin-bottom: 10px;
            color: #333;
        }
        .container {
            width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }
        .error {
            color: red;
        }
        span{
            color: red;
            margin-left: 10px;
            font-size: 14px;
            display: block;
        }
    </style>
</head>
<body>
<?php 
include 'config.php';

$errors = [];
$fname = $lname = $dob = $gender = $email = $phone = $username = $password = "";


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
   
    if (empty($_POST['fname'])) {
        $errors['fname'] = "First Name is required";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $_POST['fname'])) {
        $errors['fname'] = "Only letters and white space allowed";
    } else {
        $fname = htmlspecialchars($_POST['fname']);
    }

  
    if (empty($_POST['lname'])) {
        $errors['lname'] = "Last Name is required";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $_POST['lname'])) {
        $errors['lname'] = "Only letters and white space allowed";
    } else {
        $lname = htmlspecialchars($_POST['lname']);
    }

    
    if (empty($_POST['dob'])) {
        $errors['dob'] = "Birthday is required";
    } else {
        $dob = htmlspecialchars($_POST['dob']);
    }

   
    if (empty($_POST['gender'])) {
        $errors['gender'] = "Gender is required";
    } else {
        $gender = htmlspecialchars($_POST['gender']);
    }


    if (empty($_POST['email'])) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    } else {
        $email = htmlspecialchars($_POST['email']);
    }

   
    if (empty($_POST['phone'])) {
        $errors['phone'] = "Phone number is required";
    } elseif (!preg_match("/^\+?[0-9]{10,15}$/", $_POST['phone'])) {
        $errors['phone'] = "Invalid phone number format";
    } else {
        $phone = htmlspecialchars($_POST['phone']);
    }

 
    if (empty($errors)) {
        
        $year = date('y'); 
        $school = 'RP'; 
        $randomNumber = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT); 
        $regNumber = $year . $school . $randomNumber;

       
        $stmt = $conn->prepare("SELECT * FROM students WHERE phone=? OR email=?");
        $stmt->bind_param("ss", $phone, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['email'] == $email) {
                $errors['email'] = "Email already exists";
            }
            if ($row['phone'] == $phone) {
                $errors['phone'] = "Phone number already exists";
            }
        } else {
            
            $sql = "INSERT INTO students (fname, lname, dob, gender, email, phone, reg_number, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", $fname, $lname, $dob, $gender, $email, $phone, $regNumber);
            if ($stmt->execute()) {
                header("location:index.php?msg=New record created successfully with Registration Number:  $regNumber");
                exit;
            }
        }
    }
}

?>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" name="fname" value="<?php echo $fname; ?>">
                <span class="error"><?php echo $errors['fname'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" name="lname" value="<?php echo $lname; ?>">
                <span class="error"><?php echo $errors['lname'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <label for="birthday">Birthday:</label>
                <input type="date" name="dob" value="<?php echo $dob; ?>">
                <span class="error"><?php echo $errors['dob'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <input type="radio" name="gender" value="male" <?php echo ($gender == 'male') ? 'checked' : ''; ?>> Male
                <input type="radio" name="gender" value="female" <?php echo ($gender == 'female') ? 'checked' : ''; ?>> Female
                <span class="error"><?php echo $errors['gender'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" value="<?php echo $email; ?>">
                <span class="error"><?php echo $errors['email'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="tel" name="phone" value="<?php echo $phone; ?>">
                <span class="error"><?php echo $errors['phone'] ?? ''; ?></span>
            </div>
           
            <div class="form-group">
                <input type="submit" value="Submit">
            </div>
        </form>
    </div>
</body>
</html>
