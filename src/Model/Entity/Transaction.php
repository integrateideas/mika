<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Transaction Entity
 *
 * @property int $id
 * @property float $transaction_amount
 * @property string $stripe_charge_id
 * @property bool $status
 * @property string $remark
 * @property int $user_card_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property bool $payment_captured
 *
 * @property \App\Model\Entity\UserCard $user_card
 * @property \App\Model\Entity\Appointment[] $appointments
 */
class Transaction extends Entity
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
        'transaction_amount' => true,
        'stripe_charge_id' => true,
        'status' => true,
        'remark' => true,
        'user_card_id' => true,
        'created' => true,
        'modified' => true,
        'payment_captured' => true,
        'user_card' => true,
        'appointments' => true
    ];
}
