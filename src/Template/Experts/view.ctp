<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\Expert $expert
  */
?>
<!-- <div class="experts view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h(implode(" ", [$expert->user->first_name,$expert->user->last_name])) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Expert Name') ?></th>
            <td><?= h(implode(" ", [$expert->user->first_name,$expert->user->last_name])) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Salon Name') ?></th>
            <td><?= $expert->user_salon->salon_name ? $expert->user_salon->salon_name : "NULL" ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->

    <div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title">
        <h4><?= __('Related Expert Availabilities') ?></h4>
        </div>
        <?php if (!empty($expert->expert_availabilities)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Expert Id') ?></th>
                <th scope="col"><?= __('Available From') ?></th>
                <th scope="col"><?= __('Available To') ?></th>
                <th scope="col"><?= __('Overlapping Allowed') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($expert->expert_availabilities as $expertAvailabilities): ?>
            <tr>
                <td><?= h($expertAvailabilities->id) ?></td>
                <td><?= h($expertAvailabilities->expert_id) ?></td>
                <td><?= h($expertAvailabilities->available_from) ?></td>
                <td><?= h($expertAvailabilities->available_to) ?></td>
                <td><?= h($expertAvailabilities->overlapping_allowed) ?></td>
                <td><?= h($expertAvailabilities->status) ?></td>
                <td><?= h($expertAvailabilities->created) ?></td>
                <td><?= h($expertAvailabilities->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ExpertAvailabilities', 'action' => 'view', $expertAvailabilities->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ExpertAvailabilities', 'action' => 'edit', $expertAvailabilities->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ExpertAvailabilities', 'action' => 'delete', $expertAvailabilities->id], ['confirm' => __('Are you sure you want to delete # {0}?', $expertAvailabilities->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        </div><!-- .ibox-content end -->
        <?php endif; ?>
        </div><!-- ibox end-->
    </div><!-- .col-lg-12 end-->
    </div><!-- .row end-->
    <div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title">
        <h4><?= __('Related Expert Cards') ?></h4>
        </div>
        <?php if (!empty($expert->expert_cards)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Expert Id') ?></th>
                <th scope="col"><?= __('Stripe Customer Id') ?></th>
                <th scope="col"><?= __('Stripe Card Id') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Is Deleted') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($expert->expert_cards as $expertCards): ?>
            <tr>
                <td><?= h($expertCards->id) ?></td>
                <td><?= h($expertCards->expert_id) ?></td>
                <td><?= h($expertCards->stripe_customer_id) ?></td>
                <td><?= h($expertCards->stripe_card_id) ?></td>
                <td><?= h($expertCards->status) ?></td>
                <td><?= h($expertCards->is_deleted) ?></td>
                <td><?= h($expertCards->created) ?></td>
                <td><?= h($expertCards->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ExpertCards', 'action' => 'view', $expertCards->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ExpertCards', 'action' => 'edit', $expertCards->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ExpertCards', 'action' => 'delete', $expertCards->id], ['confirm' => __('Are you sure you want to delete # {0}?', $expertCards->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        </div><!-- .ibox-content end -->
        <?php endif; ?>
        </div><!-- ibox end-->
    </div><!-- .col-lg-12 end-->
    </div><!-- .row end-->
    <div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title">
        <h4><?= __('Related Expert Locations') ?></h4>
        </div>
        <?php if (!empty($expert->expert_locations)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Expert Id') ?></th>
                <th scope="col"><?= __('Country') ?></th>
                <th scope="col"><?= __('State') ?></th>
                <th scope="col"><?= __('City') ?></th>
                <th scope="col"><?= __('Street') ?></th>
                <th scope="col"><?= __('Zipcode') ?></th>
                <th scope="col"><?= __('Address1') ?></th>
                <th scope="col"><?= __('Address2') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($expert->expert_locations as $expertLocations): ?>
            <tr>
                <td><?= h($expertLocations->id) ?></td>
                <td><?= h($expertLocations->expert_id) ?></td>
                <td><?= h($expertLocations->country) ?></td>
                <td><?= h($expertLocations->state) ?></td>
                <td><?= h($expertLocations->city) ?></td>
                <td><?= h($expertLocations->street) ?></td>
                <td><?= h($expertLocations->zipcode) ?></td>
                <td><?= h($expertLocations->address1) ?></td>
                <td><?= h($expertLocations->address2) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ExpertLocations', 'action' => 'view', $expertLocations->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ExpertLocations', 'action' => 'edit', $expertLocations->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ExpertLocations', 'action' => 'delete', $expertLocations->id], ['confirm' => __('Are you sure you want to delete # {0}?', $expertLocations->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        </div><!-- .ibox-content end -->
        <?php endif; ?>
        </div><!-- ibox end-->
    </div><!-- .col-lg-12 end-->
    </div><!-- .row end-->
    <div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title">
        <h4><?= __('Related Expert Specialization Services') ?></h4>
        </div>
        <?php if (!empty($expert->expert_specialization_services)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Expert Id') ?></th>
                <th scope="col"><?= __('Expert Specialization Id') ?></th>
                <th scope="col"><?= __('Specialization Service Id') ?></th>
                <th scope="col"><?= __('Price') ?></th>
                <th scope="col"><?= __('Description') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col"><?= __('Duration') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($expert->expert_specialization_services as $expertSpecializationServices): ?>
            <tr>
                <td><?= h($expertSpecializationServices->id) ?></td>
                <td><?= h($expertSpecializationServices->expert_id) ?></td>
                <td><?= h($expertSpecializationServices->expert_specialization_id) ?></td>
                <td><?= h($expertSpecializationServices->specialization_service_id) ?></td>
                <td><?= h($expertSpecializationServices->price) ?></td>
                <td><?= h($expertSpecializationServices->description) ?></td>
                <td><?= h($expertSpecializationServices->created) ?></td>
                <td><?= h($expertSpecializationServices->modified) ?></td>
                <td><?= h($expertSpecializationServices->duration) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ExpertSpecializationServices', 'action' => 'view', $expertSpecializationServices->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ExpertSpecializationServices', 'action' => 'edit', $expertSpecializationServices->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ExpertSpecializationServices', 'action' => 'delete', $expertSpecializationServices->id], ['confirm' => __('Are you sure you want to delete # {0}?', $expertSpecializationServices->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        </div><!-- .ibox-content end -->
        <?php endif; ?>
        </div><!-- ibox end-->
    </div><!-- .col-lg-12 end-->
    </div><!-- .row end-->
    <div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title">
        <h4><?= __('Related Expert Specializations') ?></h4>
        </div>
        <?php if (!empty($expert->expert_specializations)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Expert Id') ?></th>
                <th scope="col"><?= __('Specialization Id') ?></th>
                <th scope="col"><?= __('Description') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($expert->expert_specializations as $expertSpecializations): ?>
            <tr>
                <td><?= h($expertSpecializations->id) ?></td>
                <td><?= h($expertSpecializations->expert_id) ?></td>
                <td><?= h($expertSpecializations->specialization_id) ?></td>
                <td><?= h($expertSpecializations->description) ?></td>
                <td><?= h($expertSpecializations->created) ?></td>
                <td><?= h($expertSpecializations->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ExpertSpecializations', 'action' => 'view', $expertSpecializations->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ExpertSpecializations', 'action' => 'edit', $expertSpecializations->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ExpertSpecializations', 'action' => 'delete', $expertSpecializations->id], ['confirm' => __('Are you sure you want to delete # {0}?', $expertSpecializations->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        </div><!-- .ibox-content end -->
        <?php endif; ?>
        </div><!-- ibox end-->
    </div><!-- .col-lg-12 end-->
    </div><!-- .row end-->

<!-- </div> -->

