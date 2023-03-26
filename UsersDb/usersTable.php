<?php
require_once 'db.php';
// Data Base Connection
$db = connect_pdo();
$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Email RegEx
    $emailPattern = "/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/";

    // Password RegEx 
    $passPattern = "/^[a-z0-9_]{8,}$/";

    // Error Handling
    function validateImage()
    {
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $file_name = $_FILES['image']['name'];
        $maxSize = 1024 * 1024 * 2;

        if (!in_array($file_extension, $allowed_extensions)) {
            return false;
        }
        if ($_FILES['image']['size'] > $maxSize) {
            return false;
        }
        // Check if the folder exists and create it if not
        if (!file_exists("images")) {
            mkdir("images");
        }
        // Upload Image to Images Folder
        move_uploaded_file($_FILES['image']['tmp_name'], "images/{$file_name}");
        return true;
    }

    // Image Error
    if (!$_FILES['image'] || !validateImage())
        $errors['image'] = "Image is Required and Must be a Valid one and not Larger thn 2mb";

    // Validate Data
    foreach ($_POST as $key => $val) {
        if ($key == "name" && (!isset($_POST['name']) || strlen($_POST['name']) < 3)) {
            $errors[$key] = "Name is Required and Must be More than 3 Chars";
        } elseif ($key == "mail" && (!isset($_POST['mail']) || !preg_match_all($emailPattern, $_POST['mail'])))
            $errors[$key] = "Email is Required and Must be a Valid One";
        elseif ($key == "password" && (!isset($_POST['password']) || !preg_match_all($passPattern, $_POST['password'])))
            $errors[$key] = "Password is Required and Must be More than 8 Chars";
        elseif ($key == "cpass" && (!isset($_POST['cpass']) || ($_POST['cpass'] != $_POST['password'])))
            $errors[$key] = "Confirm Password is Required and Must be the Same as Password";
        elseif ($key == "room" && !in_array($_POST['room'], ["a1", "a2", "a3"]))
            $errors[$key] = "Room is Required";
    }

    if ($errors) {
        header("Location:form.php?errors=" . json_encode($errors) . "&old=" . json_encode($_POST));
    } else {
        try {

            // // Insert Data
            $stmt = $db->prepare("INSERT INTO users (name, email, password, room, image) VALUES (:name, :email, :password, :room, :image)");
            $stmt->execute([
                'name' => $_POST['name'],
                'email' => $_POST['mail'],
                'password' => $_POST['password'],
                'room' => $_POST['room'],
                'image' => $_FILES['image']['name']
            ]);

            // Redirect to Users Table
            header("Location:usersTable.php");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    exit;
}
// Get all data to 
$users = [];
$stmt = $db->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll();

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
                    <th colspan="12" class="text-center fs-1"><a href="form.php">Add New User</a>
                    </th>
                </tr>
                <tr>
                    <th colspan="12" class="text-center fs-1">Users Table
                    </th>
                </tr>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Room</th>
                    <th>Image</th>
                    <th colspan="2">Actions</th>

                </tr>
            </head>

            <body>
                <?php
                foreach ($users as $user) {
                    $image = "images/{$user[5]}";
                    echo "
                <tr>
                    <td>{$user[0]}</td>
                    <td>{$user[1]}</td>
                    <td>{$user[2]}</td>
                    <td>{$user[3]}</td>
                    <td>{$user[4]}</td>
                    <td><img src='{$image}' width='100px' height='100px'></td>
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