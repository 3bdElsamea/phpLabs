<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $allowed = ["fname", "lname", "address", "country", "gender", "skills", "user", "password", "dept"];
    $required = ["fname", "lname", "gender", "user", "password"];
    $errors = [];
    // $_POST = array_intersect_key($_POST, array_flip($allowed));
    // var_dump($_POST); ???????? not working
    foreach ($allowed as $key) {
        if (!isset($_POST[$key])) {
            $errors[$key] = "This Field is required";
        } else {
            if ($key == "gender" && !in_array($_POST['gender'], ["male", "female"]))
                $errors[$key] = "Gender is one Of [male,female].";
            elseif ($key == "password" && strlen($_POST['password']) < 8)
                $errors[$key] = "Password is required and can't be empty and can't be less than 8";
            elseif ($key == "country" && !in_array($_POST['country'], ["EG", "TU", "UAE"]))
                $errors[$key] = "Country Must be one Of [Egypt,Tunisia,UAE].";
            elseif ($key == "fname" && strlen($_POST['fname']) < 3)
                $errors[$key] = "First Name is required and can't be empty and can't be less than 3";
            elseif ($key == "lname" && strlen($_POST['lname']) < 3)
                $errors[$key] = "Last Name is required and can't be empty and can't be less than 3";
        }
    }
    if ($errors) {
        header("Location:main.php?errors=" . json_encode($errors) . "&old=" . json_encode($_POST));
    } else {
        try {
            $file = fopen("users.txt", "a+");
            $id = microtime(true) * 10000;
            $skills = implode(",", $_POST['skills']);
            $data = "{$id}:{$_POST['fname']}:{$_POST['lname']}:{$_POST['gender']}:{$_POST['address']}:{$_POST['country']}:{$skills}:{$_POST['user']}:{$_POST['password']}:{$_POST['dept']}\n";
            fwrite($file, $data);
            fclose($file);
            header("Location:users.php");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    exit;
}
$users = file("users.txt");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
        crossorigin="anonymous">
</head>

<body>
    <div class="container my-5">
        <table
            class="table  table-dark table-striped table-hover table-bordered rounded text-center align-middle">

            <head>
                <tr>
                    <th colspan="12" class="text-center fs-1"><a href="main.php">Add New User</a>
                    </th>
                </tr>
                <tr>
                    <th colspan="12" class="text-center fs-1">Users Table
                    </th>
                </tr>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Address</th>
                    <th>Country</th>
                    <th>Skills</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Department</th>
                    <th colspan="2">Actions</th>

                </tr>
            </head>

            <body>
                <?php
                foreach ($users as $user) {
                    $user = explode(":", $user);
                    echo "
                <tr>
                    <td>{$user[0]}</td>
                    <td>{$user[1]}</td>
                    <td>{$user[2]}</td>
                    <td>{$user[3]}</td>
                    <td>{$user[4]}</td>
                    <td>{$user[5]}</td>
                    <td>{$user[6]}</td>
                    <td>{$user[7]}</td>
                    <td>{$user[8]}</td>
                    <td>{$user[9]}</td>
                    <td><a href='update.php?id={$user[0]}' class='btn btn-warning'>Update</a></td>
                    <td><a href='delete.php?id={$user[0]}' class='btn btn-danger'>Delete</a></td>
                </tr>
                ";
                }
                ; ?>
            </body>
        </table>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous">
        </script>
</body>

</html>