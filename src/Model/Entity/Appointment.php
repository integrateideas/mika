<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Appointment Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $expert_id
 * @property int $expert_availability_id
 * @property bool $is_confirmed
 * @property bool $is_completed
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $transaction_id
 * @property int $user_card_id
 * @property string $notes
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Expert $expert
 * @property \App\Model\Entity\ExpertAvailability $expert_availability
 * @property \App\Model\Entity\Transaction $transaction
 * @property \App\Model\Entity\UserCard $user_card
 * @property \App\Model\Entity\AppointmentService[] $appointment_services
 * @property \App\Model\Entity\AppointmentReview $appointment_review
 */
class Appointment extends Entity
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
        'user_id' => true,
        'expert_id' => true,
        'expert_availability_id' => true,
        'is_confirmed' => true,
        'is_completed' => true,
        'created' => true,
        'modified' => true,
        'transaction_id' => true,
        'user_card_id' => true,
        'notes' => true,
        'user' => true,
        'expert' => true,
        'expert_availability' => true,
        'transaction' => true,
        'user_card' => true,
        'appointment_services' => true,
        'appointment_review' => true
    ];
}
