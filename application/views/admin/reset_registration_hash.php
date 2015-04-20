
<!doctype html>
<html lang="en">
<head>

</head>

<body>
<?php echo validation_errors(); ?>
<?php echo form_open('admin/reset_registration_hash'); ?>
    <label for="email">Email address</label>
    <input type="email" name="email" id="email">

  <button type="submit">Submit</button>
</form>
</body>
</html>