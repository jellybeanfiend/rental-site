<!-- Modal -->
<div class="modal fade" id="contact-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="contact-modal-label">Reservation</h4>
      </div>
      <div class="modal-body">
      
        <form class="contact-form" action="<?=site_url("pages/process/")?>" method="post">
            <input type="hidden" id="rental" name="Rental">

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
            <label for="name">Name<span class="text-danger">*</span></label>
            <input type="email" id="name" class="form-control required" name="Name" placeholder="Name">
            </div>
            <div class="form-group">
              <label for="email">Email<span class="text-danger">*</span></label>
              <input type="email" class="form-control required" id="email required" name="Email" placeholder="Email">
            </div>
            <div class="form-group">
              <label for="phone">Phone</label>
              <input type="text" class="form-control" id="phone" name="Phone" placeholder="Phone">
            </div>
          </div>
          <div class="col-md-6">
             <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="arrival">Arrival<span class="text-danger">*</span></label>
                <input type="text" class="form-control required" id="arrival" name="Arrival" placeholder="MM/DD/YYYY">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="depart">Departure<span class="text-danger">*</span></label>
                <input type="text" class="form-control required" id="depart" name="Departure" placeholder="MM/DD/YYYY">
              </div>
            </div>
            </div>
          <div class="form-horizontal">
            <!-- <div class="col-md-3"><label for="adults">Adults<span class="text-danger">*</span></label></div><div class="col-md-3"><input type="text" class="form-control required" name="Adults" id="adults"></div>
            <div class="col-md-3"><label for="children">Children<span class="text-danger">*</span></label></div><div class="col-md-3"><input type="text" class="form-control required" name="Children" id="children"></div> -->
            <div class="form-group">
                    <label class="control-label col-sm-3">Adults</label>
                    
                    <div class="col-sm-3">
                      <input name="numBedrooms" type="text" class="form-control required">
                    </div>
                    <label class="col-sm-3 control-label">Children</label>
                    
                    <div class="col-sm-3">
                      <input name="numBathrooms" type="text" class="form-control required">
                    </div>
                  </div>
            </div>
          <div class="form-group">
            <label for="comments">Comments</label>
            <textarea rows="3" class="form-control" id="comments" name="Comments" placeholder="Comments"></textarea>
          </div>
          </div>

        </div>
          
         
        </form>
      </div>
      <div class="modal-footer">
      <p class="pull-left"><span class="text-danger">*</span> = Required</p>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary contact-submit">Submit</button>
      </div>
    </div>
  </div>
</div>