<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UserDeviceToken $userDeviceToken
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit User Device Token'), ['action' => 'edit', $userDeviceToken->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete User Device Token'), ['action' => 'delete', $userDeviceToken->id], ['confirm' => __('Are you sure you want to delete # {0}?', $userDeviceToken->id)]) ?> </li>
        <li><?= $this->Html->link(__('List User Device Tokens'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User Device Token'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="userDeviceTokens view large-9 medium-8 columns content">
    <h3><?= h($userDeviceToken->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $userDeviceToken->has('user') ? $this->Html->link($userDeviceToken->user->id, ['controller' => 'Users', 'action' => 'view', $userDeviceToken->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Device Token') ?></th>
            <td><?= h($userDeviceToken->device_token) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($userDeviceToken->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($userDeviceToken->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($userDeviceToken->modified) ?></td>
        </tr>
    </table>
</div>
