
        <?php if($admin) { $this->load->view('modals/add_pending_user'); $this->load->view('modals/confirm_modal');} ?>


    <div class="container-fluid">

    	<div class="row">
    	<div class="col-lg-8 col-lg-offset-2">

		<!-- Page Heading -->
	    <div class="row header-row">
	        <div class="col-lg-12">
	            <h1 class="page-header pull-left">
	                Manage Users
	            </h1>
	            <div class="pull-right">
	            	<button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#add-pending-user"><span class="glyphicon glyphicon-plus"></span> Add User</a>
	            </div>
	        </div>
	    </div>
	    <!-- /.row -->

		<div class="row">
			<div class="col-lg-12">
					

						<div class="table-responsive">
						<table class="table data-table table-hover table-striped" id="users-table">
							<thead>
								<tr>
									<th data-type="string" data-asc="true">User</th>
									<th data-type="string" data-asc="true">Status</th>
									<th data-type="string" data-asc="true">View/Edit</th>
									<th width="230" data-type="date" data-asc="true">Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($users as $user){ ?>
									<tr data-id="<?=$user->id?>">
										<td><strong><?=$user->name ?></strong><br /><small><?=$user->email ?></small></td>
										<td><?= ($user->frozen) ? '<span class="label label-default">frozen</span>' : (($user->status == 'pending') ? '<span class="label label-warning">pending</span>' : '<span class="label label-success">registered</span>') ?></td>
										<td>
											<div class="btn-group">
												<a class="btn btn-default" href="<?=site_url("/user/contract/$user->id") ?>">Contract</a>
												<a class="btn btn-default" href="<?=site_url("/user/profile/$user->id") ?>">Profile</a>
												<a class="btn btn-default" href="<?=site_url("/user/expenses/$user->id") ?>">Expenses</a>
												</div>
										</td>
										<td><div class="btn-group"><button class="btn btn-default freeze"><?= $user->frozen ? "Unfreeze" : "Freeze" ?></button>
										<?= ($user->status == 'pending') ? '<button class="btn btn-default resend">Resend Email</a>' : '' ?></div></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						</div>

			<!-- /#col-lg-12 -->
			</div>
		<!-- /#row -->
		</div>
	</div>
</div>
</div>