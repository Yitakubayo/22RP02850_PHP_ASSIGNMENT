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

      
        if (empty($_POST['username'])) {
            $errors['username'] = "Username is required";
        } elseif (strlen($_POST['username']) < 5 || strlen($_POST['username']) > 20) {
            $errors['username'] = "Username must be between 5 and 20 characters";
        } elseif (!preg_match("/^[a-zA-Z0-9_]*$/", $_POST['username'])) {
            $errors['username'] = "Username can only contain letters, numbers, and underscores";
        } else {
            $username = htmlspecialchars($_POST['username']);
        }

       
        if (empty($_POST['password'])) {
            $errors['password'] = "Password is required";
        } elseif (strlen($_POST['password']) < 8) {
            $errors['password'] = "Password must be at least 8 characters";
        } elseif (!preg_match("/[A-Z]/", $_POST['password'])) {
            $errors['password'] = "Password must contain at least one uppercase letter";
        } elseif (!preg_match("/[a-z]/", $_POST['password'])) {
            $errors['password'] = "Password must contain at least one lowercase letter";
        } elseif (!preg_match("/[0-9]/", $_POST['password'])) {
            $errors['password'] = "Password must contain at least one number";
        } elseif (!preg_match("/[\W]/", $_POST['password'])) {
            $errors['password'] = "Password must contain at least one special character";
        } else {
            $password = htmlspecialchars($_POST['password']);
        }

       
        if (empty($errors)) {
            $stmt = $conn->prepare("SELECT * FROM users WHERE username =? OR email=? OR phone=?");
            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("sss", $username, $email, $phone);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $errors['username'] = "Username already exists";
                $errors['email'] = "Email already exists";
                $errors['phone'] = "Phone already exists";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (fname, lname, dob, gender, email, phone, username, password, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                if (!$stmt) {
                    die("Prepare failed: " . $conn->error);
                }
                $stmt->bind_param("ssssssss", $fname, $lname, $dob, $gender, $email, $phone, $username, $hashed_password);
                if ($stmt->execute()) {
                    echo "New record created successfully";
                    exit;
                } else {
                    die("Execute failed: " . $stmt->error);
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
                <input type="email" id="email" name="email" value="<?php echo $email; ?>">
                <span class="error"><?php echo $errors['email'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="tel" name="phone" value="<?php echo $phone; ?>">
                <span class="error"><?php echo $errors['phone'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" value="<?php echo $username; ?>">
                <span class="error"><?php echo $errors['username'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" value="<?php echo $password; ?>">
                <span class="error"><?php echo $errors['password'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" value="Submit">
            </div>
            <div class="form-group">
                <a href="login.php">click here to login if you already have account</a>
            </div>
        </form>
    </div>
</body>
</html>
