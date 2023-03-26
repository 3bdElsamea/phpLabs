<?php
// Ignore Warnning
$warning = error_reporting(0);
$errors = [];
$old = [];
if ($_GET) {
    $errors = json_decode($_GET['errors'], true);
    $old = json_decode($_GET['old'], true);
}

?>
<!DOCTYPE html>
<html lang="en">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
    crossorigin="anonymous" />

<head>
    <meta charset="UTF-8" />
    <title>Log In</title>
</head>

<body>
    <!-- Form -->
    <div class="container my-5 ">
        <form method="post" action="loginValidation.php" enctype="multipart/form-data">
            <?php if ($errors && isset($errors['login']) && !empty($errors['login'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errors['login'] ?>
                </div>
            <?php } ?>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="mail" class="form-control"
                    value="<?php echo ($old && $old['mail']) ? $old['mail'] : '' ?> " />
            </div>
            <?php if ($errors && isset($errors['mail']) && !empty($errors['mail'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errors['mail'] ?>
                </div>
            <?php } ?>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" name="password" class="form-control"
                    id="exampleInputPassword1" />
            </div>
            <?php if ($errors && isset($errors['password']) && !empty($errors['password'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errors['password'] ?>
                </div>
            <?php } ?>

            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-primary col-4">Log In</button>
            </div>
        </form>
    </div>

    <!-- BS Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</body>

</html>