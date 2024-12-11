<!doctype html>
<html lang="en">
<head>
    <title>Total War Fandom</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <meta charset=utf-8>    
</head>

<body>
    <br>
    <?php include('navigation_admins.php'); ?>

    <div id="container">
        <div id="content">
            <h2>Registered Users</h2>
            <p>
                <?php
                    require("mysqli_connect.php");
                    $q = "SELECT fname, lname, email, 
                    DATE_FORMAT(registration_date,'%M %d, %Y') AS regdat, user_id FROM users ORDER BY user_id ASC";
                    $result = @mysqli_query($dbcon, $q);
                    if($result) {
                        echo '<table><tr><th>Name</th><th>Email</th><th>Registered Date</th><th>Actions</th></tr>';
                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                            echo '<tr>
                                    <td>'.$row['lname'].', '.$row['fname'].'</td>
                                    <td>'.$row['email'].'</td>
                                    <td>'.$row['regdat'].'</td>
                                    <td>
                                        <a href="edit_user.php?id='.$row["user_id"].'"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                        <a href="delete_user.php?id='.$row["user_id"].'"><i class="fa-solid fa-trash-can"></i> Delete</a>
                                    </td>
                                  </tr>';
                        }
                        echo '</table>';    
                        mysqli_free_result($result);
                    } else {
                        echo '<p class="error">The current users could not be retrieved. Contact the admin administrators.</p>';
                    }
                    mysqli_close($dbcon);                     
                ?>
            </p>
        </div>
    </div>

</body>
</html>