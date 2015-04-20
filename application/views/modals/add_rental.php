<div class="errors"></div>
				<form id="rental-form" action="<?=site_url("admin/process_rental/")?>" method="post" class="form-horizontal" enctype="multipart/form-data" role="form">
					
					<div role="tabpanel">
						<ul class="nav nav-tabs" role="tablist">
						    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Text</a></li>
						    <li role="presentation"><a href="#images" aria-controls="images" role="tab" data-toggle="tab">Images</a></li>
						    <li role="presentation"><a href="#rates" aria-controls="rates" role="tab" data-toggle="tab">Rates</a></li>
						</ul>

						<div class="tab-content">
							<!--Text tab-->
							<div role="tabpanel" class="tab-pane active" id="home">
								<input name="id" type="hidden">
								<input name="type" type="hidden">
								<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-sm-3 control-label">Category</label>
										<div class="col-sm-9">
										<select style="margin-bottom: 0px" class="form-control" name="category" id="category">
										  <option value="luxury">Luxury Villas</option>
										  <option value="vacation">Vacation Homes</option>
										  <option value="casitas">Casitas &amp; Cabanas</option>
										  <option value="boutique">Boutique Lodging</option>
										  <option value="hotels">Hotels</option>
										</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Name</label>
										<div class="col-sm-9">
											<input name="name" type="text" class="form-control required">
										</div>
									</div>
								</div> <!-- /col-md-6 -->
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label col-sm-3">bedrooms</label>
										
										<div class="col-sm-3">
											<input name="numBedrooms" type="text" class="form-control required">
										</div>
										<label class="col-sm-3 control-label">bathrooms</label>
										
										<div class="col-sm-3">
											<input name="numBathrooms" type="text" class="form-control required">
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-6">
											<div class="input-group">
												<div class="input-group-addon">$</div>
												<input type="text" name="startingPrice" id="amount" class="form-control required">
											</div>
										</div>
										<div class="col-sm-6">
											<select class="form-control" name="pricePer">
												<option value="night">per night</option>
												<option value="week">per week</option>
											</select>
										</div>
									</div>
								</div><!-- /col-md-6 -->
								</div> <!-- /row -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Description</label>
									<div class="col-sm-10">
										<textarea name="description" rows="3" class="form-control required"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Amenities</label>
									<div class="col-sm-10">
										<textarea placeholder="Start each heading with ** on a new line. Start each bullet with a dash on a new line." name="amenities" rows="3" class="form-control required"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Bedrooms</label>
									<div class="col-sm-10">
										<textarea placeholder="Start each heading with a ** on a new line. Start each bullet with a dash on a new line." name="bedrooms" rows="3" class="form-control required"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Services</label>
									<div class="col-sm-10">
										<textarea placeholder="Start each heading with a ** on a new line. Start each bullet with a dash on a new line." name="services" rows="3" class="form-control required"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Policies</label>
									<div class="col-sm-10">
										<textarea placeholder="Start each bullet with a dash on a new line." name="policies" rows="3" class="form-control required"></textarea>
									</div>
								</div>
							</div>

							<!--Image tab -->
							<div role="tabpanel" class="tab-pane" id="images">
								<input type="hidden" class="main-img" name="main-img">
								<input type="hidden" class="imgs" name="imgs">
								<div class="form-group">
									<!-- <div class="col-sm-12 col-sm-offset-1"> -->
										<label class="myLabel">
											<input name="userfile[]" id="files" type="file" multiple>
											<span>Add File</span>
										</label>
										<div class="row thumbnails">
  										</div>
									<!-- </div> -->
									
								</div>
							</div>

							<!-- Rates tab -->
							<div role="tabpanel" class="tab-pane" id="rates">

								<input type="hidden" name="rates">
								<div class="table-responsive">
									<table class="table table-bordered text-center rates-table">
										<thead>
											<tr>
												<th></th>
												<th>Season</th>
												<th>Dates</th>
												<th>Nightly</th>
												<th>Weekly</th>
												<th>Monthly</th>
												<th>Minimum Stay</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><input type="checkbox"></td>
												<td contenteditable></td>
												<td contenteditable></td>
												<td contenteditable></td>
												<td contenteditable></td>
												<td contenteditable></td>
												<td contenteditable></td>
											</tr>
										</tbody>
									</table>
								</div> <!-- /table-responsive -->
								<button class="delete-rates btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Delete Checked</button>

							</div>
						
						</div> <!-- /tab-content -->
					</div> <!-- /tabpanel -->
				</form>