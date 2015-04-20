        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
				    			<div class="panel-heading">Select Date</div>
				    			<div class="panel-body">
				    				<select class="form-control input-sm" id="month">
												<?php foreach($months as $index=>$month){ ?>
													<option value="<?=$index ?>"<?php echo $index == $current_month ? 'selected' : '';?>><?=$month ?></option>
												<?php } ?>
									</select>
									<select class="form-control input-sm" id="year">
										<?php foreach($years as $year){ ?>
											<option value="<?=$year?>"<?php echo $year == $current_year ? 'selected' : '';?>><?=$year?></option>
										<?php } ?>
									</select>
								</div>
							</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
				    			<div class="panel-heading">Filter</div>
				    			<div class="panel-body">
				    				<select class="form-control input-sm" id="category">
										<option selected disabled value="">Filter by category</option>
										<option value="">no filter</option>
										<?php foreach($categories as $category) { ?>
											<option value="<?=$category->text?>"><?=$category->text?></option>
										<?php } ?>
									</select>
									<select class="form-control input-sm" id="tag">
										<option selected disabled value="">Filter by tag</option>
										<option value="">no filter</option>
										<?php foreach($tags as $tag) { ?>
											<option value="<?=$tag->text?>"><?=$tag->text?></option>
										<?php } ?>
									</select>
								</div>
							</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Current Monthly Totals</div>
                                <div id="totals" class="panel-body">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
				    			<div class="panel-heading">Download</div>
				    			<div class="panel-body">
				    					<select class="form-control input-sm" name="download-when">
											<option value="current" selected>Current Month</option>
											<option value="range">Range</option>
											<option value="all">All Expenses</option>
										</select>
										<input type="hidden" name="current-month" value="<?=$current_month?>" />
										<input type="hidden" name="current-year" value="<?=$current_year?>" /> 
										<div id="range-div" class="hidden">
											
											<?php $name_prefixes = array("from", "to"); foreach($name_prefixes as $name_prefix){ ?>
											<?=$name_prefix?>
											<div class="row">
											<div class="col-lg-6 left-select">
												<select class="form-control input-sm" name="<?=$name_prefix?>-month">
													<?php foreach(cal_info(0)['abbrevmonths'] as $index=>$month){ ?>
														<option value="<?=$index ?>"<?php echo $index == $current_month ? 'selected' : '';?>><?=$month ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="col-lg-6 right-select">
												<select class="form-control input-sm" name="<?=$name_prefix?>-year">
													<?php foreach($years as $year){ ?>
														<option value="<?=$year?>"><?=$year?></option>
													<?php } ?>
												</select>
											</div>
											</div>
											<?php } ?>
										
										</div>
										<div class="text-center">
										<button class="btn btn-default btn-sm text-center">Download</button>
										</div>
								</div>
							</div>
                        </div>
                    </div>
                </div> <!-- /.col-lg-2 -->
                <div class="col-lg-10">
                    <div class="container-fluid">
                        <!-- Page Heading -->
                        <div class="row header-row">
                            <div class="col-lg-12">
                                <h1 class="page-header pull-left">
                                    Expense Report for <?=$months[$current_month]?> <?=$current_year?>
                                </h1>
                                <?php if($admin) {?>
                                <div class="pull-right">
	                				<button class="btn btn-primary btn-lg add-expense"><span class="glyphicon glyphicon-plus"></span> Add Expense</button>
	                			</div>
	                			<?php } ?>

                            </div>
                        </div><!-- /.row -->
                        <div class="row">
                            <div class="col-lg-12 no-padding">
                                <div id="no-results" class="well text-center" <?=!empty($expenses) ? 'style="display:none;"' : ''?>>There are no expenses available for the specified month and filters.</div>
                                <div class="table-responsive">
                                    <table class="table data-table table-hover table-striped" <?=empty($expenses) ? 'style="display:none;"' : ''?> id="expenses_table">
                                        <thead>
                                            <tr>
                                                <th data-type="date" data-asc="true">date</th>
                                                <th data-type="string" data-asc="true">category</th>
                                                <th data-type="string" data-asc="true">tags</th>
                                                <th data-type="string" data-asc="true">description</th>
                                                <th data-type="float" data-asc="true">amount(MXN)</th>
                                                <th data-type="float" data-asc="true">amount(USD)</th>
                                                <th data-type="string" data-asc="true">receipt</th>
												<?php if($admin) {?>
                                                <th>actions</th>
                                                <?php } ?>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($expenses as $expense){ ?>
												<tr data-id="<?=$expense->id?>">
													<td class="date"><?= date("m/d/Y", strtotime($expense->date)) ?></td>
													<td class="category"><?=$expense->category ?></td>
													<td class="tags"><?=$expense->tags ?></td>
													<td class="description"><?=$expense->description ?></td>
													<td class="amt_mxn"><?=money_format('$%i',$expense->amt_mxn) ?></td>
													<td class="amt_usd"><?=money_format('$%i',$expense->amt_usd) ?></td>
													<td><?= $expense->receipt_image ? '<button class="btn btn-default btn-sm view-receipt">View</button>' : ($admin ? '<button class="btn btn-default btn-sm upload-receipt">Upload</button>' : '') ?></td>
													<?php if($admin) {?>
														<td>
															<div class="btn-group btn-group-sm">
																<button class="edit-expense btn btn-default">Edit</button>
																<button class="delete-expense btn btn-danger">Delete</button>
															</div>
														</td>
													<?php } ?>
												</tr>
											<?php } ?>
                                        </tbody>
                                    </table>
                                </div><!-- /.table-responsive -->
                            </div><!-- /#col-lg-12 -->
                        </div><!-- /#row -->
                    </div><!-- /.container-fluid -->
                </div><!--/col-lg-10-->
            </div> <!--/row-->
        </div> <!--/container-fluid-->

        <?php if($admin) { 
        	$this->load->view('modals/add_expense');
        	$this->load->view('modals/confirm_modal');
        	$this->load->view('modals/upload_receipt');
        } $this->load->view('modals/view_receipt');?>