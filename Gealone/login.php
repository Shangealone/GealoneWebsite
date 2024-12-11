<!doctype html>
<html lang="en">
<head>
    <title>RED</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="style-reglog.css">
    <style>
        /* Global Reset */
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Bahnschrift, Arial, sans-serif; /* Use the same font */
            background-color: #0a1324; /* Dark background */
        }

        /* Main Container Styling */
        #main-container {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        /* Login Form Container */
        #login-form-container {
            background-color: #FFF5EE; /* Light neutral background to match other containers */
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
            margin: auto; /* Center the form vertically and horizontally */
        }

        /* Login Form Header */
        #login-form-container h2 {
            font-size: 28px;
            color: #0a1324; /* Match text color with other headings */
            margin-bottom: 20px;
            font-weight: bold;
        }

        /* Input Label Styling */
        .login-form-label {
            font-size: 16px;
            color: #444444; /* Dark gray for labels */
            text-align: left;
            margin-bottom: 5px;
            display: block;
        }

        /* Input Field Styling */
        .login-form-input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: #ffffff;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }

        .login-form-input:focus {
            border-color: #5999bf; /* Keep focus color consistent */
            outline: none;
        }

        /* Submit Button Styling */
        .login-form-submit {
            background-color: #5999bf; /* Blue button */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 12px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .login-form-submit:hover {
            background-color: #457c9f; /* Darker blue on hover */
        }
    </style>
</head>
<body>
    <div id="main-container">

        <div id="header">
            <img src="rockstar-logo-no-border.png" alt="rockstar-logo-no-border">
        </div>

        <div id="login-form-container">
        <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                require('mysqli_connect.php'); // Database connection
                
                $errors = [];

                // Validate email
                if (empty($_POST['email'])) {
                    $errors[] = 'Please enter your email address.';
                } else {
                    $e = trim($_POST['email']);
                }

                // Validate password
                if (empty($_POST['psword'])) {
                    $errors[] = 'Please enter your password.';
                } else {
                    $p = trim($_POST['psword']);
                }

                if (empty($errors)) {
                    // Use a prepared statement to avoid SQL injection
                    $stmt = $dbcon->prepare("SELECT user_id, fname, psword, user_level FROM users WHERE email = ?");
                    $stmt->bind_param('s', $e);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows === 1) {
                        $row = $result->fetch_assoc();
                        
                        // Verify the password using password_verify()
                        if (password_verify($p, $row['psword'])) {
                            // Password is correct and hashed
                            session_start();
                            $_SESSION['user_id'] = $row['user_id'];
                            $_SESSION['fname'] = $row['fname'];
                            $_SESSION['user_level'] = (int) $row['user_level'];
                        
                            // Redirect to the appropriate homepage
                            $url = ($_SESSION['user_level'] === 1) ? 'admin-homepage.php' : 'members-homepage.php';
                            header('Location: ' . $url);
                            exit();
                        } elseif ($p === $row['psword']) {
                            // Password is correct but unhashed
                            // Optionally re-hash the password and update the database
                            $hashed_password = password_hash($p, PASSWORD_DEFAULT);
                            $update_stmt = $dbcon->prepare("UPDATE users SET psword = ? WHERE user_id = ?");
                            $update_stmt->bind_param('si', $hashed_password, $row['user_id']);
                            $update_stmt->execute();
                            $update_stmt->close();
                        
                            session_start();
                            $_SESSION['user_id'] = $row['user_id'];
                            $_SESSION['fname'] = $row['fname'];
                            $_SESSION['user_level'] = (int) $row['user_level'];
                        
                            // Redirect to the appropriate homepage
                            $url = ($_SESSION['user_level'] === 1) ? 'admin-homepage.php' : 'members-homepage.php';
                            header('Location: ' . $url);
                            exit();
                        } else {
                            // Password is incorrect
                            echo '<p class="error">Invalid email or password.</p>';
                        }
                        
                    } else {
                        echo '<p class="error">This account does not exist. Please register first.</p>';
                    }

                    $stmt->close();
                    $dbcon->close();
                } else {
                    echo '<p class="error">Please correct the following errors:</p>';
                    foreach ($errors as $msg) {
                        echo "<p>- $msg</p>";
                    }
                }
            }
        ?>
  
            <form action="login.php" method="post">
                <h2>Login</h2>

                <p><label class="login-form-label" for="email">Email Address</label>
                <input type="email" id="email" name="email" class="login-form-input" size="30" maxlength="50" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">
                </p>

                <p><label class="login-form-label" for="psword">Password</label>
                <input type="password" id="psword" name="psword" class="login-form-input" size="30" maxlength="40" value="<?php if (isset($_POST['psword'])) echo $_POST['psword']; ?>">
                </p>

                <p><input type="submit" id="submit" name="submit" class="login-form-submit" value="Login"></p>
            </form>
        </div>

    </div>
</body>
</html>