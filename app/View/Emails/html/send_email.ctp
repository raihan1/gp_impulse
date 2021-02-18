<div>
    <p style="color: #5B5861; line-height: 22px">
        Dear <?php echo $data['Supplier']['name']; ?>,
        
        <br><br>SITE NAME: <?php echo $data['Site']['site_name']; ?>
        <br>SUBCENTER: <?php echo $data['SubCenter']['sub_center_name']; ?>
        <br>REGION: <?php echo ''; ?>
        <br>TR NUMBER: <?php echo $data['Ticket']['id']; ?>
        <br>ASSET GROUP: <?php echo $data['AssetGroup']['asset_group_name']; ?>
        <br>SUPPLIER NAME: <?php echo $data['Supplier']['name']; ?>
        <br>TR CREATION DATE: <?php echo $data['Ticket']['created']; ?>
        <br>RECEIVED AT SUPPLIER SITE: <?php echo $data['Ticket']['received_at_supplier']; ?>
        <br>TR CLASS: <?php echo $data['TrClass']['tr_class_name']; ?>
        <br>TR ISSUER: <?php echo $data['User']['name']; ?>
        <br>PROPOSED COMPLETION DATE & TIME: <?php echo $data['Ticket']['complete_date']; ?>
        <br>TR COMMENT: <?php echo $data['Ticket']['comment']; ?>
    </p>
</div>