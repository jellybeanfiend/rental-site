<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">

            <!-- Page Heading -->
            <div class="row header-row">
                <div class="col-lg-12">
                    <h1 class="page-header <?= $admin ? 'pull-left' : ''?>">
                        <?=$page_title?>
                    </h1>
                    <?php if($admin) {?>
                        <div class="pull-right" data-id="<?=$id?>">
                            <?php if($contract) { ?>
                                <button class="btn btn-danger btn-lg delete-contract"><span class="glyphicon glyphicon-remove"></span> Remove</a>
                            <?php } else{ ?>
                                <button class="btn btn-success btn-lg add-contract" data-toggle="modal" data-target="#contract-modal"><span class="glyphicon glyphicon-plus"></span> Upload</a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>

            </div><!-- /.row -->
			<?php if($contract) { ?>
                <img class="img-responsive" src="<?=site_url("$contract_url")?>">
            <?php } else { ?>
                <div class="well"><?= $admin ? 'A contract has not been uploaded for this user' : 'Your contract has not yet been uploaded.'?></div>
            <?php } ?>
			
		</div>
	</div>
</div>
        <?php if($admin) {  $this->load->view('modals/add_contract'); $this->load->view('modals/confirm_modal'); } ?>
