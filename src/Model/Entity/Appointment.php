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
 * @property int $expert_specialization_service_id
 * @property int $expert_specialization_id
 * @property bool $is_confirmed
 * @property bool $is_completed
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Expert $expert
 * @property \App\Model\Entity\ExpertAvailability $expert_availability
 * @property \App\Model\Entity\ExpertSpecializationService $expert_specialization_service
 * @property \App\Model\Entity\Service $service
 * @property \App\Model\Entity\AppointmentTransaction[] $appointment_transactions
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
        'expert_specialization_service_id' => true,
        'expert_specialization_id' => true,
        'is_confirmed' => true,
        'is_completed' => true,
        'user' => true,
        'expert' => true,
        'expert_availability' => true,
        'expert_specialization_service' => true,
        'service' => true,
        'appointment_transactions' => true
    ];
}
