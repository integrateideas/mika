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
            <legend><?= __('Edit Account Detail') ?></legend>
        </div>
        <?php
            echo $this->Form->control('account_holder_name',['required' => true]);
            echo $this->Form->control('account_number',['type' => 'number','required' => true]);
            echo $this->Form->control('routing_number',['type' => 'number','required' => true]);
            echo $this->Form->control('account_holder_type',['options' => $accountHolderType,'required' => true]);
        
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div> <!-- .ibox-content ends --> 
		</div> <!-- .ibox ends -->
	</div> <!-- .col-lg-12 ends -->
</div> <!-- .row ends -->