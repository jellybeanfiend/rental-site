 <div id="page-wrapper">
        

    <div class="container-fluid">

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					

						<div class="table-responsive">
						<table class="table data-table table-hover table-striped" id="expenses_table">
							<thead>
								<tr>
									<th data-type="date" data-asc="true">Name</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($users as $user){ ?>
									<tr>
										<td><a href="<?=site_url("/admin/expenses/$user->id");?>"><?=$user->name ?></a></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						</div>


				<!-- /#panel -->
				</div>
			<!-- /#col-lg-12 -->
			</div>
		<!-- /#row -->
		</div>