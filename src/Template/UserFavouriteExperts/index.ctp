<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="userFavouriteExperts index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('User Favourite Experts') ?></h3>
        </div>
        <div class = "ibox-content">
                    <table class = 'table' cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('expert_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userFavouriteExperts as $userFavouriteExpert): ?>
                        <tr>
                                        <td><?= $this->Number->format($userFavouriteExpert->id) ?></td>
                                        <td><?= $userFavouriteExpert->has('user') ? $this->Html->link($userFavouriteExpert->user->id, ['controller' => 'Users', 'action' => 'view', $userFavouriteExpert->user->id]) : '' ?></td>
                                        <td><?= $userFavouriteExpert->has('expert') ? $this->Html->link($userFavouriteExpert->expert->id, ['controller' => 'Experts', 'action' => 'view', $userFavouriteExpert->expert->id]) : '' ?></td>
                                        <td><?= h($userFavouriteExpert->created) ?></td>
                                        <td><?= h($userFavouriteExpert->modified) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $userFavouriteExpert->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $userFavouriteExpert->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $userFavouriteExpert->id], ['confirm' => __('Are you sure you want to delete # {0}?', $userFavouriteExpert->id)]) ?>
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
