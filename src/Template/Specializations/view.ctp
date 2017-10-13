<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\Specialization $specialization
  */
?>
<!-- <div class="specializations view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($specialization->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($specialization->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Label') ?></th>
            <td><?= h($specialization->label) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($specialization->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($specialization->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($specialization->modified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= $specialization->status ? __('Yes') : __('No'); ?></td>
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
        <h4><?= __('Related Expert Specializations') ?></h4>
        </div>
        <?php if (!empty($specialization->expert_specializations)): ?>
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
            <?php foreach ($specialization->expert_specializations as $expertSpecializations): ?>
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
    <div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title">
        <h4><?= __('Related Specialization Services') ?></h4>
        </div>
        <?php if (!empty($specialization->specialization_services)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Label') ?></th>
                <th scope="col"><?= __('Specialization Id') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($specialization->specialization_services as $specializationServices): ?>
            <tr>
                <td><?= h($specializationServices->id) ?></td>
                <td><?= h($specializationServices->name) ?></td>
                <td><?= h($specializationServices->label) ?></td>
                <td><?= h($specializationServices->specialization_id) ?></td>
                <td><?= h($specializationServices->status) ?></td>
                <td><?= h($specializationServices->created) ?></td>
                <td><?= h($specializationServices->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'SpecializationServices', 'action' => 'view', $specializationServices->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'SpecializationServices', 'action' => 'edit', $specializationServices->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'SpecializationServices', 'action' => 'delete', $specializationServices->id], ['confirm' => __('Are you sure you want to delete # {0}?', $specializationServices->id)]) ?>
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

