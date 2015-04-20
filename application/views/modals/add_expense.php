<div class="modal fade" id="expense-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="expense-modal-label"></h4>
			</div>
			<div class="modal-body">
				<div class="errors"></div>
				<form id="expense-form" action="<?=site_url("admin/process_expense/")?>" method="post" class="form-horizontal" enctype="multipart/form-data" role="form">
					<input name="id" type="hidden">
					<input name="type" type="hidden">
					<div class="form-group">
						<label class="col-sm-4 control-label">User</label>
						<div class="col-sm-6">
							<input type="hidden" name="user" value="<?=$current_user->id?>">
							<p class="form-control-static"><?=$current_user->name?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Date</label>
						<div class="col-sm-6">
							<input type="text" name="date-display" class="form-control required" id="datepicker">
							<input type="hidden" name="date" id="date">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Category</label>
						<div class="col-sm-6">
							<select name="category" class="form-control" id="category">
											<option value="">none</option>
											<?php foreach($categories as $category) { ?>
												<option value="<?=$category->text?>"><?=$category->text?></option>
											<?php } ?>
										</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Tags</label>
						<div class="col-sm-6">
							<select name="tags" class="form-control" id="tag">
											<option value="">none</option>
											<?php foreach($tags as $tag) { ?>
												<option value="<?=$tag->text?>"><?=$tag->text?></option>
											<?php } ?>
										</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Description</label>
						<div class="col-sm-6">
							<input name="description" type="text" class="form-control required">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Amount</label>
						<div class="col-sm-3">
							<div class="input-group">
								<div class="input-group-addon">$</div>
								<input type="text" name="amount" id="amount" class="form-control required">
							</div>
						</div>
						<div class="col-sm-3">
							<label class="radio-inline">
								<input type="radio" name="currency" checked value="usd">USD
							</label>
							<label class="radio-inline">
								<input type="radio" name="currency" value="mxn">MXN
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Receipt</label>
						<div class="col-sm-6">
							<input name="userfile" type="file">
						</div>
					</div>
			
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" id="expense-save" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>