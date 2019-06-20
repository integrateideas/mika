<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Salon Payouts') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= h('Salon Owner') ?></th>
                                        <th scope="col"><?= h('Salon Name') ?></th>
                                        <th scope="col"><?= h('Account Holder Name') ?></th>
                                        <th scope="col"><?= h('Bank Name') ?></th>
                                        <th scope="col"><?= h('Payout Amount') ?></th>
                                        <th scope="col"><?= h('Payout Date') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reqData as $key => $data): ?>
                        <tr>
                                        <td><?= h($key+1) ?></td>
                                        <td><?= h($data['salon_owner']) ?></td>
                                        <td><?= h($data['salon_name']) ?></td>
                                        <td><?= h($data['account_holder_name']) ?></td>
                                        <td><?= h($data['bank_name']) ?></td>
                                        <td><?= h($data['payout_amount']) ?></td>
                                        <td><?= h($data['payout_date']) ?></td>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
</div><!-- .ibox  end -->
</div><!-- .col-lg-12 end -->
</div><!-- .row end -->
