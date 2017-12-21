<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\AccountDetail $accountDetail
  */
?>
<!-- <div class="accountDetails view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($accountDetail->id) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Account Holder Name') ?></th>
            <td><?= h($accountDetail->account_holder_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Bank Code') ?></th>
            <td><?= h($accountDetail->bank_code) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Branch Name') ?></th>
            <td><?= h($accountDetail->branch_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($accountDetail->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Account Number') ?></th>
            <td><?= $this->Number->format($accountDetail->account_number) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($accountDetail->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($accountDetail->modified) ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->


<!-- </div> -->

