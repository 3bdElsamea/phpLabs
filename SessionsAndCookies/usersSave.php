<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Email RegEx
    $emailPattern = "/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/";

    // Password RegEx 
    $passPattern = "/^[a-z0-9_]{8,}$/";

    // Errors Array
    $errors = [];

    // Validate Image Function
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
            // Save user Data to File
            $file = fopen("users.txt", "a+");
            $id = microtime(true) * 10000;
            $_POST['name'] = trim($_POST['name']); // ??
            $image = $_FILES['image']['name'];
            $data = "{$id}:{$_POST['name']}:{$_POST['mail']}:{$_POST['password']}:{$_POST['room']}:{$image}\n";
            fwrite($file, $data);
            fclose($file);

            // Redirect to Login Page
            header("Location:login.php");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    exit;
}
?>