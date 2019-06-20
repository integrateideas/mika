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
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Account Holder Name') ?></th>
            <td><?= h($accountDetail->account_holder_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Account Number') ?></th>
            <td><?= h($accountDetail->account_number) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Routing Number') ?></th>
            <td><?= h($accountDetail->routing_number) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Account Holder Type') ?></th>
            <td><?= h($accountDetail->account_holder_type) ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->


<!-- </div> -->

