<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AppointmentReview Entity
 *
 * @property int $id
 * @property int $expert_id
 * @property int $user_id
 * @property int $appointment_id
 * @property int $rating
 * @property string $review
 * @property bool $is_approved
 * @property bool $is_deleted
 * @property bool $status
 * @property string $remark
 *
 * @property \App\Model\Entity\Expert $expert
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Appointment $appointment
 */
class AppointmentReview extends Entity
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
        'expert_id' => true,
        'user_id' => true,
        'appointment_id' => true,
        'rating' => true,
        'review' => true,
        'is_approved' => true,
        'is_deleted' => true,
        'status' => true,
        'remark' => true,
        'expert' => true,
        'user' => true,
        'appointment' => true
    ];
}
