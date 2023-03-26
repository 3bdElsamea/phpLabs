<?php

$errors = [];
// findUser function

if ($_POST) {
    // var_dump($_POST);
    foreach ($_POST as $key => $val) {
        if (empty($_POST[$key]))
            $errors[$key] = " Required";
    }
    if ($errors) {
        $errors = json_encode($errors);
        $old = json_encode($_POST);
        header("Location: login.php?errors=$errors&old=$old");
        exit();
    } else {

        $mail = $_POST['mail'];
        $password = $_POST['password'];
        $users = file("users.txt");
        $user = array_filter($users, function ($user) use ($mail, $password) {
            $user_info = explode(":", trim($user));
            return ($user_info && $user_info[2] == $mail && $user_info[3] == $password);
        });

        if ($user) {

            session_start();
            $_SESSION['email'] = $_POST['mail'];
            header("Location: welcome.php");
        } else {
            $errors['login'] = "Invalid Email or Password";
            $errors = json_encode($errors);
            $old = json_encode($_POST);
            header("Location: login.php?errors=$errors&old=$old");
        }
    }
}
?>