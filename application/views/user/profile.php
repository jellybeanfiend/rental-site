<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">

            <!-- Page Heading -->
            <div class="row header-row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        <?=$page_title?>
                    </h1>
                </div>
            </div><!-- /.row -->
			
			<form id="profile" data-id="<?=$current_user->id?>">
			<?php foreach($user_info as $index=>$info){ ?>
			<div class="form-group">
				<label><?=$index?></label>
				<input type="text" class="form-control" name="<?=$index?>" value="<?=$info?>"><br />
				</div>
			<?php } ?>
			<button class="btn btn-default" type="submit">Save</button>
			</form>
		</div>
	</div>
</div>