<?php
/**
  * @var \App\View\AppView $this
  */
?>
<?= $this->Html->css('plugins/dataTables/datatables.min.css') ?>
<div class="row">
    <div class="col-lg-12">
       <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Salon Owner') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= h('Name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('phone') ?></th>
                                        <th scope="col"><?= h('Role') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $key => $user): 
                        $name = [$user->first_name,$user->last_name];
                        ?>
                        <tr>
                                        <td><?= ($key + 1) ?></td>
                                        <td><?= h(implode(" ", $name)) ?></td>
                                        <td><?= h($user->email) ?></td>
                                        <td><?= h($user->phone) ?></td>
                                        <td><?= h($user->role->label) ?></td>
                                        <td class="actions">
                                        <?= '<a href='.$this->Url->build(['action' => 'view', $user->id]).' class="btn btn-xs btn-success">' ?>
                                            <i class="fa fa-eye fa-fw"></i>
                                        </a>
                                        <?= '<a href='.$this->Url->build(['action' => 'edit', $user->id]).' class="btn btn-xs btn-warning"">' ?>
                                            <i class="fa fa-pencil fa-fw"></i>
                                        </a>
                                        <?= $this->Form->postLink(__(''), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                                        </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class = 'ibox-title'>
            <h3><?= __('Salon Experts') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= h('Name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('phone') ?></th>
                                        <th scope="col"><?= h('Role') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($getExperts as $key => $expert){
                        $name = [$expert->user->first_name,$expert->user->last_name];
                        ?>
                        <tr>
                                        <td><?= ($key + 1) ?></td>
                                        <td><?= h(implode(" ", $name)) ?></td>
                                        <td><?= h($expert->user->email) ?></td>
                                        <td><?= h($expert->user->phone) ?></td>
                                        <td><?= h($expert->user->role->label) ?></td>
                                        <td class="actions">
                                        <?= '<a href='.$this->Url->build(['action' => 'view', $expert->user->id]).' class="btn btn-xs btn-success">' ?>
                                            <i class="fa fa-eye fa-fw"></i>
                                        </a>
                                        <?= $this->Form->postLink(__(''), ['action' => 'delete', $expert->user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $expert->user->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                                        </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
</div><!-- .ibox  end -->
</div><!-- .col-lg-12 end -->
</div><!-- .row end -->
<?= $this->Html->script('plugins/dataTables/datatables.min.js') ?>
<script>
    $(document).ready(function(){    
        $('.dataTables').DataTable({
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                { extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'ExampleFile'},
                {extend: 'pdf', title: 'ExampleFile'},

                {extend: 'print',
                 customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                }
                }
            ]
        });

    });
</script> 
