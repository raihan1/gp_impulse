<?php
echo $this->Form->create('User', array(
    'id' => 'formForgotPass',
    'class' => 'forget-form',
    'autocomplete' => 'off',
    'role' => 'form',
    'inputDefaults' => array('required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE),
));
?>
<h3>Forget Password ?</h3>
<p>
    Enter your email address below to reset your password.
</p>
<?php echo $this->Session->flash(); ?>
<div class="form-group">
    <label class="control-label visible-ie8 visible-ie9">Email</label>
    <div class="input-icon">
        <i class="fa fa-envelope"></i>
        <?php
        echo $this->Form->input('email', array(
            'type' => 'email',
            'class' => 'form-control placeholder-no-fix required',
            'placeholder' => 'Email',
        ));
        ?>
    </div>
</div>
<div class="form-actions">
    <a href="<?php echo $this->Html->url(array('plugin' => FALSE, 'controller' => 'users', 'action' => 'login')); ?>" id="back-btn" class="btn" style="color: #000; margin-left: 30px; background-color: rgb(221, 221, 221)"><i class="m-icon-swapleft"></i> Back</a>
    <button type="submit" class="btn green-haze pull-right">
        Submit <i class="m-icon-swapright m-icon-white"></i>
    </button>
</div>
<?php echo $this->Form->end(); ?>