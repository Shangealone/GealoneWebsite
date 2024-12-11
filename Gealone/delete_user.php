<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Delete User</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body class="delete">
    <div id="deleteUserContent">
        <h2>Delete User Record</h2>
        <?php
            if((isset($_GET["id"])) && (is_numeric($_GET["id"]))) {
                require("mysqli_connect.php");
                $id = ($_GET["id"]);
            } elseif((isset($_POST["id"])) && (is_numeric($_POST["id"]))) {
                require("mysqli_connect.php");
                $id = ($_POST["id"]);
            } else {
                echo '<div class="deleteUserError"><p>There seems to be a problem</p>
                      <p><a class="deleteUserBtn" href="index.php">Click here to get redirected</a></p></div>';
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($_POST['sure'] == 'Yes') {
                    $q = "DELETE FROM users WHERE user_id = $id";
                    $result = @mysqli_query($dbcon, $q);
                    if (mysqli_affected_rows($dbcon) == 1) {
                        echo '<div class="deleteUserSuccess"><p>Record has been deleted.</p>
                              <p><a class="deleteUserBtn" href="index.php">Go to home</a></p></div>
                              <p><a class="deleteUserBtn" href="register_view_users.php">Go to User Records</a></p>';
                    } else {
                        echo '<div class="deleteUserError"><p>The record could not be deleted due to a system error.</p></div>';
                    }
                } else {
                    echo '<div class="deleteUserError"><p>Record was not deleted.</p>
                          <p><a class="deleteUserBtn" href="index.php">Go To Home</a></p>
                          <p><a class="deleteUserBtn" href="register_view_users.php">View All Users</a></p>';
                }
            } else {
                $q = "SELECT fname, lname from users where user_id = $id";
                $result = @mysqli_query($dbcon, $q);
                if ($result && mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $fullName = $row['fname'] . ' ' . $row['lname'];
                    echo "<h3>Are you sure you want to delete the user named $fullName?</h3>";
                    echo '<form action="delete_user.php" method="post">
                            <div>
                                <button class="deleteUserBtn" type="submit" name="sure" value="Yes">Yes</button>
                                <button class="deleteUserBtn" type="submit" name="sure" value="No">No</button>
                            </div>
                            <input type="hidden" name="id" value="'.$id.'">
                          </form>';
                } else {
                    echo '<div class="deleteUserError"><h3>ID not found.
                          <a class="deleteUserBtn" href="index.php">Go to home</a></h3></div>';
                }
            }

            mysqli_close($dbcon);
        ?>
    </div>
</body>
</html>