
<!doctype html>
<html lang="en">
<head>

</head>

<body>
<?php echo validation_errors(); ?>
<?php echo form_open('admin/add_pending_user'); ?>
    <label for="email">Email address</label>
    <input type="email" name="email" id="email">

    <label for="name">Name</label>
    <input type="text" name="name" id="name">

  <button type="submit">Submit</button>
</form>
</body>
</html>