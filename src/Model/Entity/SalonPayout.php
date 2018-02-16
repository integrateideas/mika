<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SalonPayout Entity
 *
 * @property int $id
 * @property string $amount
 * @property bool $status
 * @property \Cake\I18n\FrozenTime $created
 * @property string $transfer_id
 * @property string $destination_account
 * @property string $destination_payment
 * @property int $connect_salon_account_id
 *
 * @property \App\Model\Entity\AccountDetail $account_detail
 */
class SalonPayout extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'amount' => true,
        'status' => true,
        'created' => true,
        'transfer_id' => true,
        'destination_account' => true,
        'destination_payment' => true,
        'connect_salon_account_id' => true,
        'account_detail' => true
    ];
}
