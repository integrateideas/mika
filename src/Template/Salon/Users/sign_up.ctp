<?php

$salonTemplate = [
        'button' => '<button class="btn btn-primary m-b" {{attrs}}>{{text}}</button>',
        'input' => '<input type="{{type}}" class="form-control" name="{{name}}"{{attrs}}/>',
        'inputContainer' => '<div class="form-group {{type}}{{required}}">{{content}}</div>',
        'label' => '<label class="col-sm-2 control-label" {{attrs}}>{{text}}</label>',
];

$this->Form->setTemplates($salonTemplate);

?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <legend><?= __('Sign Up') ?></legend>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($user, ['data-toggle'=>'validator','class' => 'form-horizontal'])?>
                <div class="form-group">
                    <?= $this->Form->label('first_name', __('First Name'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'name']); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('first_name', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= $this->Form->label('last_name', __('Last Name'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'name']); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('last_name', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= $this->Form->label('email', __('Email'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'lastname']); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('email', ['type' => 'email','label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= $this->Form->label('password', __('Password'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'email']); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input("password", array(
                                        "label" => false, 
                                        'required' => true,
                                        "class" => "form-control"));
                    ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= $this->Form->label('phone', __('Phone Number'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'email']); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input("phone", array(
                                        "label" => false, 
                                        'required' => true,
                                        "class" => "form-control",
                                        'type' => 'number'));
                    ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-4">
                        <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary'], 'id' => 'saveUser']) ?>
                        <?= $this->Html->link('Cancel', ['controller' =>'Users','action' => 'login'],['class' => ['btn', 'btn-danger']]);?>
                    </div>
                </div>     
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>