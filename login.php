<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
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
        span {
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
    session_start();
    $errors = [];
    $username = $password = "";

    if(isset($_SESSION['username'])){
        header("location: index.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        
        if (empty($_POST['username'])) {
            $errors['username'] = "Username is required";
        } else {
            $username = htmlspecialchars($_POST['username']);
        }

        if (empty($_POST['password'])) {
            $errors['password'] = "Password is required";
        } else {
            $password = htmlspecialchars($_POST['password']);
        }

        if (empty($errors)) {
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 0) {
                $errors['username'] = "Username not found";
            } else {
                $user = $result->fetch_assoc();
                if (!password_verify($password, $user['password'])) {
                    $errors['password'] = "Invalid password";
                } else {

                    if(isset($_POST['remember'])){
                        $_COOKIE['username']=$username;
                        $_COOKIE['password']=$password;
                        setcookie("username", $username, time() + 3600, "/");
                        setcookie("password", $password, time() + 360, "/");
                    }
                    $_SESSION['username'] = $username;
                    header("location: index.php");
                    exit();
                }
            }
        }
    }
    ?>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" value="<?php if(isset($_COOKIE['username'])){echo $_COOKIE['username'];}?>">
                <span class="error"><?php echo $errors['username'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" value="<?php if(isset($_COOKIE['password'])){echo $_COOKIE['password'];}?>">
                <span class="error"><?php echo $errors['password'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <label for="remember">remember me</label>
                <input type="checkbox" name="remember">
            </div>
            <div class="form-group">
                <input type="submit" value="Login">
            </div>
            <div class="form-group">
            <a href="createAccount.php">click here if you don't have account</a>
            </div>
        </form>
    </div>
</body>
</html>
