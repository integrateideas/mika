<div class="row">
    <div class="col-lg-12">
    <!-- <div class="connectSalonAccounts index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Connect Salon Accounts') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= h('Account Holder Name') ?></th>
                                        <th scope="col"><?= h('Account Holder Type') ?></th>
                                        <th scope="col"><?= h('Bank Name') ?></th>
                                        <th scope="col"><?= h('Routing Number') ?></th>
                                        <th scope="col"><?= h('Country') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reqData as $key => $data): ?>
                        <tr>
                                        <td><?= h($key+1) ?></td>
                                        <td><?= h($data['account_holder_name']) ?></td>
                                        <td><?= h($data['account_holder_type']) ?></td>
                                        <td><?= h($data['bank_name']) ?></td>
                                        <td><?= h($data['routing_number']) ?></td>
                                        <td><?= h($data['country']) ?></td>
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
