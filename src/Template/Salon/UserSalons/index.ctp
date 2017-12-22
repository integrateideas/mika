<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
       <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Salon Owner') ?></h3>
        </div>
        <div class = "ibox-content">
                    <table class = 'table' cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= h('Salon Owner') ?></th>
                                        <th scope="col"><?= h('Salon Name') ?></th>
                                        <th scope="col"><?= h('Location') ?></th>
                                        <th scope="col"><?= h('Zipcode') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userSalon as $key => $salon){
                            $name = [$salon->user->first_name,$salon->user->last_name];
                        ?>
                        <tr>
                                        <td><?= ($key + 1) ?></td>
                                        <td><?= h(implode(" ", $name)) ?></td>
                                        <td><?= h($salon->salon_name) ?></td>
                                        <td><?= h($salon->location) ?></td>
                                        <td><?= h($salon->zipcode) ?></td>
                                        <td class="actions">
                                        <?= '<a href='.$this->Url->build(['action' => 'view', $salon->id]).' class="btn btn-xs btn-success">' ?>
                                            <i class="fa fa-eye fa-fw"></i>
                                        </a>
                                        <?= '<a href='.$this->Url->build(['action' => 'edit', $salon->id]).' class="btn btn-xs btn-warning"">' ?>
                                            <i class="fa fa-pencil fa-fw"></i>
                                        </a>
                                        <?= $this->Form->postLink(__(''), ['action' => 'delete', $salon->id], ['confirm' => __('Are you sure you want to delete # {0}?', $salon->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                                        </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
        </div>
</div><!-- .ibox  end -->
</div><!-- .col-lg-12 end -->
</div><!-- .row end -->
