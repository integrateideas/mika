<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AppointmentService Entity
 *
 * @property int $id
 * @property int $appointment_id
 * @property int $expert_specialization_id
 * @property int $expert_specialization_service_id
 * @property bool $status
 *
 * @property \App\Model\Entity\Appointment $appointment
 * @property \App\Model\Entity\ExpertSpecialization $expert_specialization
 * @property \App\Model\Entity\ExpertSpecializationService $expert_specialization_service
 */
class AppointmentService extends Entity
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
        'expert_specialization_id' => true,
        'expert_specialization_service_id' => true,
        'status' => true,
        'appointment' => true,
        'expert_specialization' => true,
        'expert_specialization_service' => true
    ];
}
