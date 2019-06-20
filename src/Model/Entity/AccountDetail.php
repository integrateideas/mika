<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AccountDetail Entity
 *
 * @property int $id
 * @property string $account_holder_name
 * @property string $account_number
 * @property string $routing_number
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $user_salon_id
 * @property string $account_holder_type
 * @property string $stripe_bank_account_id
 * @property string $stripe_customer_id
 *
 * @property \App\Model\Entity\UserSalon $user_salon
 */
class AccountDetail extends Entity
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
        'account_holder_name' => true,
        'account_number' => true,
        'routing_number' => true,
        'created' => true,
        'modified' => true,
        'user_salon_id' => true,
        'account_holder_type' => true,
        'stripe_bank_account_id' => true,
        'stripe_customer_id' => true,
        'user_salon' => true
    ];
}
