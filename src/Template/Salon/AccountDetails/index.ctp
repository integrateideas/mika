<?php
/**
  * @var \App\View\AppView $this
  */
?>
<?= $this->Html->css('plugins/dataTables/datatables.min.css') ?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="accountDetails index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Account Details') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('account_holder_name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('account_number') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('bank_code') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('branch_name') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($accountDetails as $accountDetail): ?>
                        <tr>
                                        <td><?= $this->Number->format($accountDetail->id) ?></td>
                                        <td><?= h($accountDetail->account_holder_name) ?></td>
                                        <td><?= h($accountDetail->account_number) ?></td>
                                        <td><?= h($accountDetail->bank_code) ?></td>
                                        <td><?= h($accountDetail->branch_name) ?></td>
                                        <td class="actions">
                                            <?= '<a href='.$this->Url->build(['action' => 'view', $accountDetail->id]).' class="btn btn-xs btn-success">' ?>
                                                <i class="fa fa-eye fa-fw"></i>
                                            </a>
                                            <?= '<a href='.$this->Url->build(['action' => 'edit', $accountDetail->id]).' class="btn btn-xs btn-warning"">' ?>
                                                <i class="fa fa-pencil fa-fw"></i>
                                            </a>
                                            <?= $this->Form->postLink(__(''), ['action' => 'delete', $accountDetail->id], ['confirm' => __('Are you sure you want to delete # {0}?', $accountDetail->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                                        </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <!-- </div> -->
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
