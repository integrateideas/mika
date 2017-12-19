<?php

$loginFormTemplate = [
        'button' => '<button class="btn btn-primary full-width m-b" {{attrs}}>{{text}}</button>',
        'input' => '<input type="{{type}}" class="form-control" name="{{name}}"{{attrs}}/>',
        'inputContainer' => '<div class="form-group {{type}}{{required}}">{{content}}</div>',
        'label' => '<label class="col-sm-2 control-label" {{attrs}}>{{text}}</label>',
];

$this->Form->setTemplates($loginFormTemplate);

?>
<div>
    <h3>Welcome to Mika</h3>
    <p>Perfectly designed and precisely prepared admin theme with over 50 pages with extra new web app views.</p>
    <p>Login in. To see it in action.</p>
    <?= $this->Form->create(null,['class'=>'m-t']) ?>
        <?= $this->Form->control('username') ?>
        <?= $this->Form->control('password') ?>
        <?= $this->Form->button(__('Login')); ?>
        <?= $this->Html->link('Sign Up',['controller' =>'Users','action' => 'signUp'], ['class' => 'btn btn-danger block full-width m-b']); ?>
    <?= $this->Form->end() ?>  
    <p class="m-t"> <small>Hair Stylist Application &copy; 2017</small> </p>
</div>
