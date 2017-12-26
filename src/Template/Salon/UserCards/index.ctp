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
            <h3><?= __('Card Details') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= h('Brand') ?></th>
                                        <th scope="col"><?= h('Country') ?></th>
                                        <th scope="col"><?= h('Last 4 digits') ?></th>
                                        <th scope="col"><?= h('Card Expiry') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $key => $detail){
                        ?>
                        <tr>
                                        <td><?= ($key + 1) ?></td>
                                        <td><?= h($detail['brand']) ?></td>
                                        <td><?= h($detail['country']) ?></td>
                                        <td><?= h($detail['last4']) ?></td>
                                        <td><?= h($detail['exp_month'].'/'. $detail['exp_year']) ?></td>
                                        <td class="actions">
                                        <?= $this->Form->postLink(__(''), ['action' => 'delete', $getUserCards[$detail['id']]->id], ['confirm' => __("Are you sure you want to delete the card with last 4 digits {0}?", $detail['last4']), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
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
