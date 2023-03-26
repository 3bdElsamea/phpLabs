<?php
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
    <form method="post" action="users.php">
      <div class="mb-3">
        <label class="form-label">First Name</label>
        <input type="text" name="fname" class="form-control"
          value="<?php echo ($old && $old['fname']) ? $old['fname'] : '' ?> " />
      </div>
      <?php if ($errors && isset($errors['fname']) && !empty($errors['fname'])) { ?>
        <div class=" alert alert-danger" role="alert">
          <?php echo $errors['fname'] ?>
        </div>
      <?php } ?>
      <div class="mb-3">
        <label class="form-label">Last Name</label>
        <input type="text" name="lname" class="form-control"
          value="<?php echo ($old && $old['lname']) ? $old['lname'] : '' ?>" />
      </div>
      <?php if ($errors && isset($errors['lname']) && !empty($errors['lname'])) { ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $errors['lname'] ?>
        </div>
      <?php } ?>
      <div class="mb-3">
        <label class="form-label">Address</label>
        <input type="text" name="address" class="form-control"
          value="<?php echo ($old && $old['address']) ? $old['address'] : '' ?>" />
      </div>
      <?php if ($errors && isset($errors['address']) && !empty($errors['address'])) { ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $errors['address'] ?>
        </div>
      <?php } ?>

      <label class="form-check-label" for="inlineCheckbox1">Gender</label>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="gender" id="inlineCheckbox1"
          value="male" />
        <label class="form-check-label" for="inlineCheckbox1">Male</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="gender" id="inlineCheckbox2"
          value="female" />
        <label class="form-check-label" for="inlineCheckbox2">Female</label>
      </div>
      <?php if ($errors && isset($errors['gender']) && !empty($errors['gender'])) { ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $errors['gender'] ?>
        </div>
      <?php } ?>
      <select name="country" class="form-control my-3" aria-label="Default select example">
        <option selected>Select a Country</option>
        <option <?php echo ($old && $old['country'] && $old['country'] == 'EG') ? 'selected' : '' ?>
          value="EG">Egypt</option>
        <option <?php echo ($old && $old['country'] && $old['country'] == 'TU') ? 'selected' : '' ?>
          value="TU">Tunisia</option>
        <option <?php echo ($old && $old['country'] && $old['country'] == 'UAE') ? 'selected' : '' ?>
          value="UAE">UAE</option>
      </select>
      <?php if ($errors && isset($errors['country']) && !empty($errors['country'])) { ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $errors['country'] ?>
        </div>
      <?php } ?>
      <div class="col-md-12">
        <label class="form-label">Skills</label>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="skills[]" value="html" id="HTML" />
          <label class="form-check-label" for="HTML"> HTML </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="skills[]" value="oop" id="oop" />
          <label class="form-check-label" for="oop"> OOP </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="skills[]" value="mysql"
            id="mysql" />
          <label class="form-check-label" for="mysql"> Mysql </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="skills[]" value="php" id="php" />
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
          value="<?php echo ($old && $old['user']) ? $old['user'] : '' ?> " />
      </div>
      <?php if ($errors && isset($errors['user']) && !empty($errors['user'])) { ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $errors['user'] ?>
        </div>
      <?php } ?>
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Password</label>
        <input type="password" name="password" class="form-control" id="exampleInputPassword1" />
      </div>
      <?php if ($errors && isset($errors['password']) && !empty($errors['password'])) { ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $errors['password'] ?>
        </div>
      <?php } ?>
      <div class="mb-3">
        <label class="form-label">Department</label>
        <input type="text" name="dept" class="form-control" value="Open Source" readonly />
      </div>
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