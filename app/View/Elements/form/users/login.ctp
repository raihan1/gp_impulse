<?php
echo $this->Form->create('User', array(
    'id' => 'formLogin',
    'class' => 'login-form',
    'autocomplete' => 'off',
    'role' => 'form',
    'inputDefaults' => array('required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE),
));
?>
<h3 class="form-title">Login to your account</h3>
<div class="alert alert-danger display-hide">
    <button class="close" data-close="alert"></button>
    <span>Enter your email and password.</span>
</div>
<?php echo $this->Session->flash(); ?>
<div class="form-group">
    <label class="control-label visible-ie8 visible-ie9">Email</label>
    <div class="input-icon">
        <i class="fa fa-user"></i>
        <?php
        echo $this->Form->input('email', array(
            'type' => 'email',
            'class' => 'form-control placeholder-no-fix required',
            'placeholder' => 'Email',
        ));
        ?>
    </div>
</div>
<div class="form-group">
    <label class="control-label visible-ie8 visible-ie9">Password</label>
    <div class="input-icon">
        <i class="fa fa-lock"></i>
        <?php
        echo $this->Form->input('password', array(
            'type' => 'password',
            'class' => 'form-control placeholder-no-fix required',
            'placeholder' => 'Password',
        ));
        ?>
    </div>
</div>
<div class="form-actions">
    <button type="submit" class="btn green-haze pull-right">
        Login <i class="m-icon-swapright m-icon-white"></i>
    </button>
</div>
<div class="login-options" style="display: none">
    <h4>Or login with</h4>
    <ul class="social-icons">
        <li>
            <?php echo $this->Html->link('', '#', array('class' => 'facebook', 'data-original-title' => 'Facebook')); ?>
        </li>
        <li>
            <?php echo $this->Html->link('', '#', array('class' => 'twitter', 'data-original-title' => 'Twitter')); ?>
        </li>
        <li>
            <?php echo $this->Html->link('', '#', array('class' => 'googleplus', 'data-original-title' => 'Goole Plus')); ?>
        </li>
        <li>
            <?php echo $this->Html->link('', '#', array('class' => 'linkedin', 'data-original-title' => 'Linkedin')); ?>
        </li>
    </ul>
</div>
<div class="forget-password">
    <?php echo $this->Html->link('<h4 style="color: #5D41F5">Forgot your password ?</h4>', array('plugin' => FALSE, 'controller' => 'users', 'action' => 'forgot_password'), array('escape' => FALSE)); ?>
    <p style="display: none">
        no worries, click
        <?php echo $this->Html->link('here', array('plugin' => FALSE, 'controller' => 'users', 'action' => 'forgot_password'), array('escape' => FALSE)); ?>
        to reset your password.
    </p>
</div>
<!--<div class="create-account">
    <p>
        Don't have an account yet?
        <?php echo $this->Html->link('Create an account', array('plugin' => FALSE, 'controller' => 'users', 'action' => 'registration'), array('escape' => FALSE)); ?>
    </p>
</div>-->
<?php echo $this->Form->end(); ?>