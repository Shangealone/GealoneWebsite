<!doctype html>
<html lang="en">
<head>
    <title>Register Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body class="centered-body">
<div class="register-container">
    <?php
    $dbc = mysqli_connect('localhost', 'shangealone', 'shangealone', 'members_shangealone');
    if (!$dbc) {
        die("Database connection failed: " . mysqli_connect_error());
    }
    $errors = array();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (empty($_POST['fname'])) {
            $errors[] = 'Please enter your first name.';
        } else {
            $fn = trim($_POST['fname']);
        }
        if (empty($_POST['lname'])) {
            $errors[] = 'Please enter your last name.';
        } else {
            $ln = trim($_POST['lname']);
        }
        if (empty($_POST['email'])) {
            $errors[] = 'Please enter your email.';
        } else {
            $email = trim($_POST['email']);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Please enter a valid email address.';
            }
        }
        if (empty($_POST['psword1'])) {
            $errors[] = 'Please enter your password.';
        } else {
            $psword1 = trim($_POST['psword1']); 
            if ($psword1 != $_POST['psword2']) {
                $errors[] = 'Your passwords do not match.';
            } else {
                $p_hashed = password_hash($psword1, PASSWORD_DEFAULT);  
            }
        }
        if (empty($errors)) {
            $query = "INSERT INTO users (fname, lname, email, psword, registration_date) VALUES (?, ?, ?, ?, NOW())";
            $stmt = mysqli_prepare($dbc, $query);
            if (!$stmt) {
                die("Database query preparation failed: " . mysqli_error($dbc));
            }
            mysqli_stmt_bind_param($stmt, 'ssss', $fn, $ln, $email, $p_hashed);
            if (mysqli_stmt_execute($stmt)) {
                header('Location: register_thanks.php');
                exit();
            } else {
                echo '<h2>Error during registration. Please try again.</h2>';
            }
            mysqli_stmt_close($stmt);
        } else {
            echo '<h2>Error!</h2>
                  <p class="error">The following error(s) occurred:<br>';
            foreach ($errors as $ex) {
                echo "→ $ex<br/>";
            }
            echo '</p><h4>Please try again</h4><br/><br/>';
        }
    }
    ?>
    <div class="form-container">
        <h2 class="regis">Register</h2>
        <form action="register_page.php" method="post">
            <p>
                <label class="label" for="fname">First Name</label>
                <input type="text" id="fname" name="fname" value="<?php if (isset($_POST['fname'])) echo $_POST['fname']; ?>">
            </p>
            <p>
                <label class="label" for="lname">Last Name</label>
                <input type="text" id="lname" name="lname" value="<?php if (isset($_POST['lname'])) echo $_POST['lname']; ?>">
            </p>
            <p>
                <label class="label" for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">
            </p>
            <p>
                <label class="label" for="psword1">Password</label>
                <input type="password" id="psword1" name="psword1" value="<?php if (isset($_POST['psword1'])) echo $_POST['psword1']; ?>">
            </p>
            <p>
                <label class="label" for="psword2">Confirm Password</label>
                <input type="password" id="psword2" name="psword2" value="<?php if (isset($_POST['psword2'])) echo $_POST['psword2']; ?>">
            </p>
            <p>
                <input type="submit" id="submit" name="submit" value="Register">
            </p>
        </form>
    </div>
</div>

</body>
</html>