
<!doctype html>
<html lang="en">
<head>

</head>

<body>
<?php echo validation_errors(); ?>
<?php echo form_open('admin/add_expense'); ?>
    <label for="user" name="user">Client:</label>
    <select name="user" id="user">
    <?php foreach($users as $user){ ?>
	  <option value="<?=$user->id?>"><?=$user->name?></option>
	<?php } ?>
	</select>
	<br />
	<label for="category">Category</label>
	<select name="category" id="category">
	<?php foreach($categories as $category){ ?>
	  <option value="<?=$category?>"><?=$category?></option>
	<?php } ?>
	</select>
	<br />
	<label for="tags">Tag</label>
	<select name="tag" id="tag">
    <?php foreach($tags as $tag){ ?>
	  <option value="<?=$tag?>"><?=$tag?></option>
	<?php } ?>
	</select>
    <br />
    <label for="description">Description</label>
    <input type="text" name="description" id="description">
    <br />
    <label for="amount">Amount</label>
    <input type="text" name="amount" id="amount">
    <input type="radio" name="currency" value="mxn">MXN
	<input type="radio" name="currency" value="usd">USD
	<br />
	<label for="receipt">Receipt</label>
	<input type="file" name="receipt" size="20" />
<br />
  <button type="submit">Submit</button>
</form>
</body>
</html>