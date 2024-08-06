<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Details</title>
    <style>
       
       body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        form {
            width: 400px;
            margin: 20px auto;
            border: 1px solid #ddd;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"], 
        input[type="date"], 
        input[type="email"], 
        input[type="radio"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="radio"] {
            width: auto;
            margin-right: 5px;
        }
        input[type="radio"] + label {
            display: inline;
            margin-right: 15px;
            vertical-align: middle;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        input:focus {
            border-color: #4CAF50;
            outline: none;
        }
        .error {
            color: red;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>
<?php
include 'config.php';

if (isset($_GET['edit'])) {
    $edit = intval($_GET['edit']);
    $query = "SELECT * FROM students WHERE id=$edit";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $fname = htmlspecialchars($row['fname']);
        $lname = htmlspecialchars($row['lname']);
        $dob = htmlspecialchars($row['dob']);
        $gender = htmlspecialchars($row['gender']);
        $email = htmlspecialchars($row['email']);
        $phone = htmlspecialchars($row['phone']);
?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?edit=<?php echo $id; ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <label for="fname">First Name:</label>
            <input type="text" id="fname" name="fname" value="<?php echo $fname; ?>">
            
            <label for="lname">Last Name:</label>
            <input type="text" id="lname" name="lname" value="<?php echo $lname; ?>">
            
            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" value="<?php echo $dob; ?>">
            
            <label for="gender">Gender:</label>
            <input type="radio" id="male" name="gender" value="male" <?php if ($gender == 'male') echo 'checked'; ?>>
            <label for="male">Male</label>
            <input type="radio" id="female" name="gender" value="female" <?php if ($gender == 'female') echo 'checked'; ?>>
            <label for="female">Female</label>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>">
            
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>">
            
            <input type="submit" value="Save Changes">
        </form>
<?php
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $errors = [];
            $fname = $lname = $dob = $gender = $email = $phone = "";

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
                $errors['dob'] = "Date of Birth is required";
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
                $update = "UPDATE students SET fname='$fname', lname='$lname', dob='$dob', gender='$gender', email='$email', phone='$phone' WHERE id='$id'";
                if (mysqli_query($conn, $update)) {
                    header("location:index.php?msg=Data updated successfully");
                } else {
                    echo "Data update failed: " . mysqli_error($conn);
                }
            } else {
                foreach ($errors as $error) {
                    echo "<p style='color: red;'>$error</p>";
                }
            }
        }
    } else {
        echo "Record not found.";
    }
}
?>
</body>
</html>
