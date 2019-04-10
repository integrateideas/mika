<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="userFavouriteExperts form large-9 medium-8 columns content">
    <?= $this->Form->create($userFavouriteExpert) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Edit User Favourite Expert') ?></legend>
        </div>
        <?php
            echo $this->Form->control('user_id', ['options' => $users]);
            echo $this->Form->control('expert_id', ['options' => $experts]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div> <!-- .ibox-content ends --> 
		</div> <!-- .ibox ends -->
	</div> <!-- .col-lg-12 ends -->
</div> <!-- .row ends -->