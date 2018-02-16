<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\ConnectSalonAccount $connectSalonAccount
  */
?>
<!-- <div class="connectSalonAccounts view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($connectSalonAccount->id) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Stripe User Account Id') ?></th>
            <td><?= h($connectSalonAccount->stripe_user_account_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User Salon') ?></th>
            <td><?= $connectSalonAccount->has('user_salon') ? $this->Html->link($connectSalonAccount->user_salon->id, ['controller' => 'UserSalons', 'action' => 'view', $connectSalonAccount->user_salon->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Access Token') ?></th>
            <td><?= h($connectSalonAccount->access_token) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($connectSalonAccount->id) ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->


<!-- </div> -->

