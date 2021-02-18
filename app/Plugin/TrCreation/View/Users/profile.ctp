<style type="text/css">
    textarea {
        resize: none;
    }
    .text-left {
        text-align: left !important;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box green-sharp">
            <div class="portlet-title">
                <div class="caption">
                    <i class="glyphicon glyphicon-plus"></i> User Profile Edit
                </div>
            </div>
            
            <div class="portlet-body form">
                <?php
                echo $this->Form->create( 'User', array(
                    'id'            => 'form-user',
                    'class'         => 'form-horizontal',
                    'type'          => 'file',
                    'autocomplete'  => 'off',
                    'role'          => 'form',
                    'inputDefaults' => array( 'required' => FALSE, 'label' => FALSE, 'div' => FALSE, 'legend' => FALSE ),
                ) );
                ?>
                <div class="form-body">
                    <div id="form_result" class="alert alert-danger display-hide"></div>
                    <div id="form_error" class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        You have some form errors. Please check below.
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-3">Name</label>
                                <div class="col-md-9">
                                    <?php
                                    echo $this->Form->input( 'name', array(
                                        'type'              => 'text',
                                        'class'             => 'form-control required',
                                        'minlength'         => 2,
                                        'value'             => $data['User']['name'],
                                        'placeholder'       => 'Albert Einstein',
                                        'data-msg-required' => 'Please provide name',
                                    ) );
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Region</label>
                                <div class="col-md-9">
                                    <p class="control-label text-left"><?php echo $data['Region']['region_name']; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Office</label>
                                <div class="col-md-9">
                                    <p class="control-label text-left"><?php echo $data['SubCenter']['sub_center_name']; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Email</label>
                                <div class="col-md-9">
                                    <?php
                                    echo $this->Form->input( 'email', array(
                                        'type'              => 'email',
                                        'class'             => 'form-control required',
                                        'minlength'         => 2,
                                        'value'             => $data['User']['email'],
                                        'placeholder'       => 'abc@abc.com',
                                        'data-msg-required' => 'Please provide password',
                                    ) );
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Password</label>
                                <div class="col-md-9">
                                    <?php
                                    echo $this->Form->input( 'password', array(
                                        'type'              => 'password',
                                        'class'             => 'form-control',
                                        'minlength'         => 2,
                                        'value'             => '',
                                        'placeholder'       => 'ein123st456ein',
                                    ) );
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Phone</label>
                                <div class="col-md-9">
                                    <?php
                                    echo $this->Form->input( 'phone', array(
                                        'type'        => 'text',
                                        'class'       => 'form-control required',
                                        'digits'      => TRUE,
                                        'value'       => !empty( $data['User']['phone'] ) ? $data['User']['phone'] : '',
                                        'placeholder' => '01234567890',
                                    ) );
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-3">Address</label>
                                <div class="col-md-9">
                                    <?php
                                    echo $this->Form->input( 'address', array(
                                        'type'        => 'textarea',
                                        'class'       => 'form-control',
                                        'value'       => !empty( $data['User']['address'] ) ? $data['User']['address'] : '',
                                        'placeholder' => 'House 423, Road 30, Mohakhali DOHS, Dhaka',
                                    ) );
                                    ?>
                                </div>
                            </div>
                            <div class="fileinput fileinput-new col-md-9 col-md-offset-3" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                    <?php echo $this->Lookup->showPhoto( !empty( $data['User']['photo'] ) ? $data['User']['photo'] : '', "/resource/users/profile_photo/" . $data['User']['photo'] ); ?>
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                                <div>
                                    <span class="btn default btn-file">
                                        <span class="fileinput-new">Select Image</span>
                                        <span class="fileinput-exists">Change</span>
                                        <?php echo $this->Form->input( 'photo', array( 'type' => 'file' ) ); ?>
                                    </span>
                                    <a href="#" class="btn red fileinput-exists" data-dismiss="fileinput">Remove</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions right">
                    <button type="submit" class="btn green"><i class="fa fa-check"></i> Save</button>
                    <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Cancel', array( 'plugin' => 'tr_creation', 'controller' => 'users', 'action' => 'dashboard' ), array( 'escape' => FALSE, 'class' => 'btn red' ) ); ?>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        $( '#form-user' ).validate_popover( { popoverPosition: 'top' } );
        
        $( document ).bind( 'click', function( e ) {
            $( '.popover' ).popover( 'hide' );
        } );
    } );
</script>