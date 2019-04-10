<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\User $user
  */
$name = [$userSalon->user->first_name,$userSalon->user->last_name];
?>
<!-- <div class="users view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h('Salon Details') ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Salon Name') ?></th>
            <td><?= h($userSalon->salon_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Salon Owner') ?></th>
            <td><?= h(implode(" ", $name)) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Location') ?></th>
            <td><?= h($userSalon->location) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Zipcode') ?></th>
            <td><?= h($userSalon->zipcode) ?></td>
        </tr>
    </table> <!-- table end-->
    <div class="row">
        <div class="col-lg-12 text-center">
            <?= $this->Html->link('Back',$this->request->referer(),['class' => ['btn', 'btn-warning']]);?>
        </div>
    </div>
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->
