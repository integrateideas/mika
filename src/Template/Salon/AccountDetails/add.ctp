<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="accountDetails form large-9 medium-8 columns content">
    <?= $this->Form->create($accountDetail) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add Account Detail') ?></legend>
        </div>
        <?php
            echo $this->Form->control('account_holder_name');
            echo $this->Form->control('account_number');
            echo $this->Form->control('bank_code');
            echo $this->Form->control('branch_name');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div>
		</div>
	</div>
</div>