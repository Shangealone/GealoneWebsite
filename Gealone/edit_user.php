<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Edit User</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

</head>
<body class="edit">
    <!-- Main Content -->
    <div id="editUserContent">
        <h2>Edit User Record</h2>
        <?php
        if ((isset($_GET["id"])) && (is_numeric($_GET["id"]))) {
            require("mysqli_connect.php");
            $id = $_GET["id"];
        } elseif ((isset($_POST["id"])) && (is_numeric($_POST["id"]))) {
            require("mysqli_connect.php");
            $id = $_POST["id"];
        } else {
            echo '<div class="editUserError"><p>There seems to be a problem</p>
                  <p><a class="editUserBtn" href="index.php">Click here to get redirected</a></p></div>';
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
            $fname = mysqli_real_escape_string($dbcon, trim($_POST['fname']));
            $lname = mysqli_real_escape_string($dbcon, trim($_POST['lname']));

            if (!empty($fname) && !empty($lname)) {
                $q = "UPDATE users SET fname = '$fname', lname = '$lname' WHERE user_id = $id";
                $result = @mysqli_query($dbcon, $q);
                if (mysqli_affected_rows($dbcon) == 1) {
                    echo '<div class="editUserSuccess"><p>User record updated successfully.</p>
                          <p><a class="editUserBtn" href="index.php">Go to Home</a></p>
                          <p><a class="editUserBtn" href="register_view_users.php">View All Users</a></p></div>';
                } else {
                    echo '<div class="editUserError"><p>System error. Unable to update record.</p></div>';
                }
            } else {
                echo '<div class="editUserError"><p>Please fill in all fields.</p></div>';
            }
        } else {
            $q = "SELECT fname, lname FROM users WHERE user_id = $id";
            $result = @mysqli_query($dbcon, $q);
            if ($result && mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $fname = $row['fname'];
                $lname = $row['lname'];

                echo '<form action="edit_user.php" method="post">
                        <div>
                            <label for="fname">First Name:</label>
                            <input type="text" id="fname" name="fname" value="' . $fname . '" required>
                        </div>
                        <div>
                            <label for="lname">Last Name:</label>
                            <input type="text" id="lname" name="lname" value="' . $lname . '" required>
                        </div>
                        <div>
                            <button class="editUserBtn" type="submit" name="submit">Update</button>
                        </div>
                        <input type="hidden" name="id" value="' . $id . '">
                      </form>';
            } else {
                echo '<div class="editUserError"><h3>ID not found.
                      <a class="editUserBtn" href="index.php">Go to home</a></h3></div>';
            }
        }

        mysqli_close($dbcon);
        ?>
    </div>
</body>
</html>