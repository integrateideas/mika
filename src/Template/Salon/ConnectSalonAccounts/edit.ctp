<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="connectSalonAccounts form large-9 medium-8 columns content">
    <?= $this->Form->create($connectSalonAccount) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Edit Connect Salon Account') ?></legend>
        </div>
        <?php
            echo $this->Form->control('stripe_user_account_id');
            echo $this->Form->control('user_salon_id', ['options' => $userSalons]);
            echo $this->Form->control('access_token');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div> <!-- .ibox-content ends --> 
		</div> <!-- .ibox ends -->
	</div> <!-- .col-lg-12 ends -->
</div> <!-- .row ends -->