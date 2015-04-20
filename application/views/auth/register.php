
<!doctype html>

<html lang="en">
<head>
      <title>site - Property Management made easy</title>
    <link href="<?=base_url().'assets/bootstrap/css/bootstrap.min.css'?>" rel="stylesheet">
    <link href="<?=base_url().'assets/css/signin.css'?>" rel="stylesheet">
        <link href="<?=base_url().'assets/css/test.css'?>" rel="stylesheet">


</head>
<body>

<div class="container">
<div class="form-signin">
  <?php echo form_open('authentication/register/'.$hash, array('class' => 'form-signin', 'id' => 'register')); ?>

          <h2 class="form-signin-heading">Confirm Registration</h2>
          <div class="alert alert-danger hidden"></div>
          <?php echo validation_errors(); ?>
          <input type="password" class="form-control" name="password" id="password" placeholder="Password" pattern=".{8,}" required title="Password must be atleast 8 characters" required autofocus>
          <input type="password" class="form-control" name="passwordconf" id="passwordconf" placeholder="Confirm Password" required>
          <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
        </form>
        </div>
        </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>

    <script src="<?=base_url().'assets/js/script.js'?>"></script>

</body>
</html>