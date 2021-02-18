<div class="col-md-12 col-sm-12 col-xs-12" style="margin: 15px 0px 30px 0px; border-bottom: 1px solid #FDDADA;">
    <div class="row">
        <label class="col-md-2 col-sm-2 col-xs-12 control-label">TR No:</label>
        <div class="col-md-4 col-sm-10 col-xs-12">
            <?php echo $data['Ticket']['id']; ?>
        </div>
        <label class="col-md-2 col-sm-2 col-xs-12 control-label">TR Creation Date:</label>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <?php echo $this->Lookup->showDateTime( $data['Ticket']['created'] ); ?>
        </div>
    </div>
    <div class="row">
        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Office:</label>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <?php echo $data['Ticket']['sub_center']; ?>
        </div>
        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Site Name:</label>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <?php echo $data['Ticket']['site']; ?>
        </div>
    </div>
    <!--div class="row">
        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Asset Group:</label>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <?php echo $data['Ticket']['asset_group']; ?>
        </div>
        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Asset Number:</label>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <?php echo $data['Ticket']['asset_number']; ?>
        </div>
    </div-->
    <div class="row">
        <label class="col-md-2 col-sm-2 col-xs-12 control-label">TR Class:</label>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <?php echo $data['Ticket']['tr_class']; ?>
        </div>
        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Supplier:</label>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <?php echo $data['Ticket']['supplier']; ?>
        </div>
    </div>
    <div class="row">
        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Received at Supplier:</label>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <?php echo $this->Lookup->showDateTime( $data['Ticket']['received_at_supplier'] ); ?>
        </div>
        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Proposed Completion Date:</label>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <?php echo $this->Lookup->showDateTime( $data['Ticket']['complete_date'] ); ?>
        </div>
    </div>
    <div class="row">
        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Comment</label>
        <div class="col-md-10 col-sm-10 col-xs-12">
            <?php echo nl2br( $data['Ticket']['comment'] ); ?>
        </div>
    </div>
</div>