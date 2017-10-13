<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\SpecializationService $specializationService
  */
?>
<!-- <div class="specializationServices view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($specializationService->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($specializationService->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Label') ?></th>
            <td><?= h($specializationService->label) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Specialization') ?></th>
            <td><?= $specializationService->has('specialization') ? $this->Html->link($specializationService->specialization->name, ['controller' => 'Specializations', 'action' => 'view', $specializationService->specialization->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($specializationService->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($specializationService->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($specializationService->modified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= $specializationService->status ? __('Yes') : __('No'); ?></td>
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
        <h4><?= __('Related Expert Specialization Services') ?></h4>
        </div>
        <?php if (!empty($specializationService->expert_specialization_services)): ?>
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
            <?php foreach ($specializationService->expert_specialization_services as $expertSpecializationServices): ?>
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

<!-- </div> -->

