<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SalonPayout Entity
 *
 * @property int $id
 * @property string $payout_id
 * @property string $amount
 * @property bool $status
 * @property int $account_detail_id
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Payout $payout
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
        'payout_id' => true,
        'amount' => true,
        'status' => true,
        'account_detail_id' => true,
        'created' => true,
        // 'payout' => true,
        'account_detail' => true
    ];
}
