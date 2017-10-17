<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="experts index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Experts') ?></h3>
        </div>
        <div class = "ibox-content">
                    <table class = 'table' cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                                        <th scope="col">id</th>
                                        <th scope="col">Expert Name</th>
                                        <th scope="col">Salon Name</th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($experts as $expert): 
                        $name = [$expert->user->first_name,$expert->user->last_name];
                        ?>
                        <tr>
                                        <td><?= $this->Number->format($expert->id) ?></td>
                                        <td><?= h(implode(" ", $name)) ?></td>
                                        <td><?= $expert->user_salon->salon_name ? $expert->user_salon->salon_name : "NULL" ?></td>
                            <td class="actions">
                                        <?= '<a href='.$this->Url->build(['action' => 'view', $expert->id]).' class="btn btn-xs btn-success">' ?>
                                            <i class="fa fa-eye fa-fw"></i>
                                        </a>
                                        <?= '<a href='.$this->Url->build(['action' => 'edit', $expert->id]).' class="btn btn-xs btn-warning"">' ?>
                                            <i class="fa fa-pencil fa-fw"></i>
                                        </a>
                                        <?= $this->Form->postLink(__(''), ['action' => 'delete', $expert->id], ['confirm' => __('Are you sure you want to delete # {0}?', $expert->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                                        </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
        </div>
        
        
    <!-- </div> -->
</div><!-- .ibox  end -->
</div><!-- .col-lg-12 end -->
</div><!-- .row end -->
