<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ConnectSalonAccount Entity
 *
 * @property int $id
 * @property string $stripe_user_account_id
 * @property int $user_salon_id
 * @property string $access_token
 *
 * @property \App\Model\Entity\StripeUserAccount $stripe_user_account
 * @property \App\Model\Entity\UserSalon $user_salon
 */
class ConnectSalonAccount extends Entity
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
        'stripe_user_account_id' => true,
        'user_salon_id' => true,
        'access_token' => true,
        'stripe_user_account' => true,
        'user_salon' => true
    ];
}
