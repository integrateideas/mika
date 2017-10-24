<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\UserFavouriteExpert $userFavouriteExpert
  */
?>
<!-- <div class="userFavouriteExperts view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($userFavouriteExpert->id) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $userFavouriteExpert->has('user') ? $this->Html->link($userFavouriteExpert->user->id, ['controller' => 'Users', 'action' => 'view', $userFavouriteExpert->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Expert') ?></th>
            <td><?= $userFavouriteExpert->has('expert') ? $this->Html->link($userFavouriteExpert->expert->id, ['controller' => 'Experts', 'action' => 'view', $userFavouriteExpert->expert->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($userFavouriteExpert->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($userFavouriteExpert->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($userFavouriteExpert->modified) ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->


<!-- </div> -->

