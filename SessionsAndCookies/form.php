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
    <title>Add User</title>
</head>

<body>
    <!-- Form -->
    <div class="container mt-3">
        <form method="post" action="usersSave.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control"
                    value="<?php echo ($old && $old['name']) ? $old['name'] : '' ?> " />
            </div>
            <?php if ($errors && isset($errors['name']) && !empty($errors['name'])) { ?>
                <div class=" alert alert-danger" role="alert">
                    <?php echo $errors['name'] ?>
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
            <!-- Confirm Pass -->
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Confirm Password</label>
                <input type="password" name="cpass" class="form-control"
                    id="exampleInputPassword1" />
            </div>
            <?php if ($errors && isset($errors['cpass']) && !empty($errors['cpass'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errors['cpass'] ?>
                </div>
            <?php } ?>
            <div class="mb-3">
                <select name="room" class="form-control my-3" aria-label="Default select example">
                    <option selected>Select a Room</option>
                    <option <?php echo ($old && $old['room'] && $old['room'] == 'a1') ? 'selected' : '' ?> value="a1">Application 1</option>
                    <option <?php echo ($old && $old['room'] && $old['room'] == 'a2') ? 'selected' : '' ?> value="a2">Application 2</option>
                    <option <?php echo ($old && $old['room'] && $old['room'] == 'a3') ? 'selected' : '' ?> value="a3">Application 3</option>
                </select>
            </div>
            <?php if ($errors && isset($errors['room']) && !empty($errors['room'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errors['room'] ?>
                </div>
            <?php } ?>
            <!-- Upload Image -->
            <div class="mb-3">
                <label for="formFile" class="form-label">Upload Image</label>
                <input class="form-control" name="image" type="file" id="formFile" />
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

    <!-- BS Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</body>

</html>