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
                <h5><?= __('Add Salon') ?></h5>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($userSalon, ['data-toggle'=>'validator','class' => 'form-horizontal'])?>
                <div class="form-group">
                    <?= $this->Form->label('salon_name', __('Salon Name'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'name']); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('salon_name', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= $this->Form->label('location', __('Location'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'lastname']); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('location', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= $this->Form->label('zipcode', __('Zipcode'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'email']); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input("zipcode", array(
                                        "label" => false, 
                                        'required' => true,
                                        "class" => "form-control",
                                        'type' => 'number'));
                    ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10">
                        <label class="col-sm-offset-6">
                            <?= $this->Form->checkbox('status', ['label' => false,'required' => true]); ?> Active
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-4">
                        <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary'], 'id' => 'saveUser']) ?>
                        <?= $this->Html->link('Cancel', $this->request->referer(),['class' => ['btn', 'btn-danger']]);?>
                    </div>
                </div>     
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>