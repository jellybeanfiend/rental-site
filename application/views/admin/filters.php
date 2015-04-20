
    <div class="container-fluid">
    <div class="row">
    <div class="col-md-8 col-md-offset-2">

		<!-- Page Heading -->
	    <div class="row header-row">
	        <div class="col-lg-12">
	            <h1 class="page-header">
	                Manage Filters
	            </h1>
	        </div>
	    </div>
	    <!-- /.row -->

		<div class="row">
			<div class="col-lg-6">
				<div class="panel panel-default" data-type="category">
					<div class="panel-heading">Categories</div>
					<ul class="list-group" data-type="category">
						<?php foreach($categories as $category){ ?>
							<li class="list-group-item"><?=$category->text?><button title="delete" class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-trash"></span></button></li>
						<?php } ?>
					</ul>
					<div class="panel-body">
						<div class="input-group">
								<input type="text" class="form-control success-addon" placeholder="New Category">
								<span class="input-group-btn">
									<button class="btn btn-success add-filter" type="button">
										<span class="glyphicon glyphicon-plus"></span>
										</button>
								</span>
							</div>
					</div>
				<!-- /#panel -->
				</div>
			<!-- /#col-lg-6 -->
			</div>
			<div class="col-lg-6">
				<div class="panel panel-default" data-type="tag">
					<div class="panel-heading">Tags</div>

						<ul class="list-group">
							<?php foreach($tags as $tag){ ?>
								<li class="list-group-item"><?=$tag->text?><button title="delete" class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-trash"></span></button></li>
							<?php } ?>
						</ul>
						<div class="panel-body">
						<div class="input-group">
									<input type="text" class="form-control success-addon" placeholder="New Tag">
									<span class="input-group-btn">
										<button class="btn btn-success add-filter" type="button">
											<span class="glyphicon glyphicon-plus"></span>
											</button>
									</span>
								</div>
						</div>


				<!-- /#panel -->
				</div>
			<!-- /#col-lg-6 -->
			</div>
		<!-- /#row -->
		</div>
	</div>
	</div>
</div>
<?php $this->load->view('modals/confirm_modal.php'); ?>