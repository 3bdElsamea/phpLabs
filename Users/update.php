<?php
error_reporting(0);

$errors = [];
if ($_GET) {
    $errors = json_decode($_GET['errors'], true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Error Handling
    echo $_POST['id'];
    $allowed = ["id", "fname", "lname", "address", "country", "gender", "skills", "user", "password", "dept"];
    $required = ["id", "fname", "lname", "gender", "user", "password"];
    $errors = [];
    $_POST = array_intersect_key($_POST, array_flip($allowed));
    // var_dump($_POST);

    // Error Messages
    foreach ($allowed as $key) {
        if (!isset($_POST[$key])) {
            $errors[$key] = $key . " is required";
        } else {
            if (in_array($key, $required) && empty($_POST[$key]))
                $errors[$key] = "This Field is required and can't be empty";
            elseif ($key == "gender" && !in_array($_POST['gender'], ["male", "female"]))
                $errors[$key] = $key . " is one Of [male,female].";
            elseif ($key == "password" && strlen($_POST['password']) < 8)
                $errors[$key] = $key . " is required and can't be empty and can't be less than 8";
            elseif ($key == "user" && !isset($_POST['user']))
                $errors[$key] = "Email is required and can't be empty";
            elseif ($key == "country" && !in_array($_POST['country'], ["EG", "UK", "US"]))
                $errors[$key] = $key . " is one Of [EG,UK,US].";
            elseif ($key == "fname" && strlen($_POST['fname']) < 3)
                $errors[$key] = $key . " is required and can't be empty and can't be less than 3";
            elseif ($key == "lname" && strlen($_POST['lname']) < 3)
                $errors[$key] = $key . " is required and can't be empty and can't be less than 3";
        }
    }
    // Erroe or Update User Data
    if ($errors) {
        header("Location:update.php?id={$_POST['id']}&errors=" . json_encode($errors));
    } else {
        $users = file("users.txt");
        $skills = implode(",", $_POST['skills']);
        foreach ($users as $key => $user) {
            if (explode(":", $user)[0] == $_POST['id']) {
                $users[$key] = "{$_POST['id']}:{$_POST['fname']}:{$_POST['lname']}:{$_POST['gender']}:{$_POST['address']}:{$_POST['country']}:{$skills}:{$_POST['user']}:{$_POST['password']}:{$_POST['dept']}\n";
                break;
            }
        }
        file_put_contents("users.txt", implode("", $users));
        header("Location:users.php");
    }
    exit;
}

// Fill the Form
$user = [];
if ($_SERVER['REQUEST_METHOD'] == "GET" && $_GET["id"]) {
    $id = $_GET['id'];
    $users = file("users.txt");
    $users = array_filter($users, function ($user) use ($id) {
        $user = explode(":", $user);
        return $user[0] == $id;
    });
    foreach ($users as $user) {
        $user = explode(":", trim($user));
        $user[6] = explode(",", $user[6]);
        break;
    }
    if (!$user) {
        header("location:users.php");
        exit;
    }
} ?>

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
        <form method="POST" action="update.php">
            <input type="hidden" name="id" value="<?php echo $user[0] ?>">
            <div class="mb-3">
                <label for="fname" class="form-label">First Name </label>
                <input name="fname" type="text" class="form-control" id="fname"
                    value="<?php echo $user[1] ?>">
                <?php if ($errors && isset($errors['fname']) && !empty($errors['fname'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $errors['fname'] ?>
                    </div>
                <?php } ?>
            </div>
            <div class="mb-3">
                <label for="lname" class="form-label">Last Name </label>
                <input name="lname" type="text" class="form-control" id="lname"
                    value="<?php echo $user[2] ?>">
                <?php if ($errors && isset($errors['lname']) && !empty($errors['lname'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $errors['lname'] ?>
                    </div>
                <?php } ?>

            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control"
                    value="<?php echo $user[4] ?>" />
            </div>
            <?php if ($errors && isset($errors['address']) && !empty($errors['address'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errors['address'] ?>
                </div>
            <?php } ?>

            <div class="row my-3">
                <div class="col-2">
                    <label for="gender" class="form-label">Gender</label>
                </div>
                <div class="form-check col-2">
                    <input class="form-check-input" type="radio" name="gender" id="male"
                        value="male" <?php echo ($user[3] == 'male') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check col-2">
                    <input class="form-check-input" type="radio" name="gender" id="female"
                        value="female" <?php echo ($user[3] == 'female') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="female">Female</label>
                </div>
                <?php if ($errors && isset($errors['gender']) && !empty($errors['gender'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $errors['gender'] ?>
                    </div>
                <?php } ?>
            </div>
            <select name="country" class="form-select" aria-label="Default select example">
                <option disabled>Select a Country</option>
                <option <?php echo ($user[5] == 'EG') ? 'selected' : '' ?> value="EG">Egypt</option>
                <option <?php echo ($user[5] == 'UK') ? 'selected' : '' ?> value="TU">Tunisia
                </option>
                <option <?php echo ($user[5] == 'US') ? 'selected' : '' ?> value="UAE">UAE</option>
            </select>
            <?php if ($errors && isset($errors['country']) && !empty($errors['country'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errors['country'] ?>
                </div>
            <?php } ?>

            <div class="col-md-12">
                <label class="form-label">Skills</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="skills[]" value="html"
                        id="HTML" <?php echo (in_array("html", $user[6])) ? 'checked' : '' ?> />
                    <label class="form-check-label" for="HTML"> HTML </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="skills[]" value="oop"
                        id="oop" <?php echo (in_array("oop", $user[6])) ? 'checked' : '' ?> />
                    <label class="form-check-label" for="oop"> OOP </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="skills[]" value="mysql"
                        id="mysql" <?php echo (in_array("mysql", $user[6])) ? 'checked' : '' ?> />
                    <label class="form-check-label" for="mysql"> Mysql </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="skills[]" value="php"
                        id="php" <?php echo (in_array("php", $user[6])) ? 'checked' : '' ?> />
                    <label class="form-check-label" for="php"> PHP </label>
                </div>
            </div>
            <?php if ($errors && isset($errors['skills']) && !empty($errors['skills'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errors['skills'] ?>
                </div>
            <?php } ?>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="user" class="form-control"
                    value="<?php echo $user[7] ?>" />
            </div>
            <?php if ($errors && isset($errors['user']) && !empty($errors['user'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errors['user'] ?>
                </div>
            <?php } ?>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input name="password" type="text" class="form-control" id="password"
                    value="<?php echo $user[8] ?>">
                <?php if ($errors && isset($errors['password']) && !empty($errors['password'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $errors['password'] ?>
                    </div>
                <?php } ?>
            </div>
            <div class="mb-3">
                <label for="dept" class="form-label">Department</label>
                <input type="text" name="dept" class="form-control" value="Open Source" readonly />
            </div>

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