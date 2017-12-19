<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="accountDetails index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Account Details') ?></h3>
        </div>
        <div class = "ibox-content">
                    <table class = 'table' cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('account_holder_name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('account_number') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('bank_code') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('branch_name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($accountDetails as $accountDetail): ?>
                        <tr>
                                        <td><?= $this->Number->format($accountDetail->id) ?></td>
                                        <td><?= h($accountDetail->account_holder_name) ?></td>
                                        <td><?= $this->Number->format($accountDetail->account_number) ?></td>
                                        <td><?= h($accountDetail->bank_code) ?></td>
                                        <td><?= h($accountDetail->branch_name) ?></td>
                                        <td><?= h($accountDetail->created) ?></td>
                                        <td><?= h($accountDetail->modified) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $accountDetail->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $accountDetail->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $accountDetail->id], ['confirm' => __('Are you sure you want to delete # {0}?', $accountDetail->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
        </div>
        
        <div class="paginator">
            <ul class="pagination">
                <?= $this->Paginator->first('<< ' . __('first')) ?>
                <?= $this->Paginator->prev('< ' . __('previous')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('next') . ' >') ?>
                <?= $this->Paginator->last(__('last') . ' >>') ?>
            </ul>
            <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
        </div>
    <!-- </div> -->
</div><!-- .ibox  end -->
</div><!-- .col-lg-12 end -->
</div><!-- .row end -->
