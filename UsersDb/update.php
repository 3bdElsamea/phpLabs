<?php
$warnning = error_reporting(0);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// Database Connection
require_once 'db.php';
$db = connect_pdo();
$errors = [];
if ($_GET) {
    $errors = json_decode($_GET['   '], true);
}
$user = [];
$imagePath = "";
// Get User Data
if ($_GET["id"]) {
    $id = $_GET["id"];
    $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $user = $stmt->fetchObject();
    // var_dump($user);
    $imagePath = "images/{$user->image}";

    if (!$user) {
        header("location:usersTable.php");
        exit;
    }
}

// Update User
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $user = $stmt->fetchObject();
    // var_dump($user);
    $imagePath = "images/{$user->image}";

    // Email RegEx
    $emailPattern = "/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/";

    // Password RegEx 
    $passPattern = "/^[a-z0-9_]{8,}$/";

    $errors = [];


    function validateImage()
    {
        if (!empty($_FILES['image']['name'])) {
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
        }
        return true;
    }

    // Image Error
    if (!validateImage())
        $errors['image'] = "Image Must be a Valid one and not Larger thn 2mb";

    // Validate Data
    foreach ($_POST as $key => $val) {
        if ($key == "name" && (!isset($_POST['name']) || strlen($_POST['name']) < 3)) {
            $errors[$key] = "Name is Required and Must be More than 3 Chars";
        } elseif ($key == "mail" && (!isset($_POST['mail']) || !preg_match_all($emailPattern, $_POST['mail'])))
            $errors[$key] = "Email is Required and Must be a Valid One";
        elseif ($key == "password" && (!isset($_POST['password']) || strlen($_POST['password']) < 8))
            $errors[$key] = "Password is Required and Must be More than 8 Chars";
        elseif ($key == "cpass" && (!isset($_POST['cpass']) || $_POST['password'] != $_POST['cpass']))
            $errors[$key] = "Confirm Password is Required and Must be the Same as Password";
        elseif ($key == "room" && !in_array($_POST['room'], ["a1", "a2", "a3"]))
            $errors[$key] = "Room is Required";

    }

    // Erroe or Update User Data
    if ($errors) {
        header("Location:update.php?id={$_POST['id']}&errors=" . json_encode($errors));
    } else {
        // Update User Data
        $id = $_POST['id'];
        $name = $_POST['name'];
        $mail = $_POST['mail'];
        $password = $_POST['password'];
        $room = $_POST['room'];
        $image = !empty($_FILES['image']['name']) ? $_FILES['image']['name'] : $user->image;
        // var_dump($_POST);
        $stmt = $db->prepare("UPDATE users SET name = :name, email = :mail, password = :password, room = :room, image = :image WHERE id = :id");
        $stmt->execute([':name' => $name, ':mail' => $mail, ':password' => $password, ':room' => $room, ':image' => $image, ':id' => $id]);
        header("Location:usersTable.php");
    }
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
        crossorigin="anonymous">

</head>

<body>

    <div class="container my-4">
        <h1 class="text-center text-success">Update User</h1>
        <hr>
        <form method="POST" action="update.php" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $user->id ?>">
            <!-- Name -->
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control"
                    value="<?php echo $user->name ?> " />
            </div>
            <?php if ($errors && isset($errors['name']) && !empty($errors['name'])) { ?>
                <div class=" alert alert-danger" role="alert">
                    <?php echo $errors['name'] ?>
                </div>
            <?php } ?>
            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="mail" class="form-control"
                    value="<?php echo $user->email ?>" />
            </div>
            <?php if ($errors && isset($errors['mail']) && !empty($errors['mail'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errors['mail'] ?>
                </div>
            <?php } ?>
            <!-- Password -->
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" name="password" class="form-control"
                    id="exampleInputPassword1" value="<?= $user->password ?>" />
            </div>
            <?php if ($errors && isset($errors['password']) && !empty($errors['password'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errors['password'] ?>
                </div>
            <?php } ?>
            <!-- Confirm Pass -->
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Confirm Password</label>
                <input type="password" name="cpass" class="form-control" id="exampleInputPassword1"
                    value="<?= $user->password ?>" />
            </div>
            <?php if ($errors && isset($errors['cpass']) && !empty($errors['cpass'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errors['cpass'] ?>
                </div>
            <?php } ?>
            <!-- Room -->
            <div class="mb-3">
                <select name="room" class="form-control my-3" aria-label="Default select example">
                    <option selected>Select a Room</option>
                    <option <?php echo ($user->room == 'a1') ? 'selected' : '' ?> value="a1">
                        Application
                        1</option>
                    <option <?php echo ($user->room == 'a2') ? 'selected' : '' ?> value="a2">
                        Application
                        2</option>
                    <option <?php echo ($user->room == 'a3') ? 'selected' : '' ?> value="a3">
                        Application
                        3</option>
                </select>
            </div>
            <?php if ($errors && isset($errors['room']) && !empty($errors['room'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errors['room'] ?>
                </div>
            <?php } ?>
            <!-- Image -->
            <div class="mb-3">
                <label for="formFile" class="form-label">Upload Image</label>
                <input class="form-control" name="image" type="file" id="formFile"
                    value="<?php echo $imagePath ?>" />
            </div>
            <?php if ($errors && isset($errors['image']) && !empty($errors['image'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errors['image'] ?>
                </div>
            <?php } ?>

            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-primary col-4">Submit</button>
                <button type="reset" class="btn btn-danger col-4">Reset</button>
            </div>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous">
        </script>
</body>

</html>