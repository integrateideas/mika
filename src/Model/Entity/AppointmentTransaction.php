<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AppointmentTransaction Entity
 *
 * @property int $id
 * @property int $appointment_id
 * @property int $transaction_amount
 * @property string $charge_id
 * @property bool $status
 * @property string $remark
 *
 * @property \App\Model\Entity\Appointment $appointment
 * @property \App\Model\Entity\Charge $charge
 */
class AppointmentTransaction extends Entity
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
        'appointment_id' => true,
        'transaction_amount' => true,
        'charge_id' => true,
        'status' => true,
        'remark' => true,
        'appointment' => true,
        'charge' => true
    ];
}
