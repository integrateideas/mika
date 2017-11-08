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
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $transaction_id
 * @property int $user_card_id
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Expert $expert
 * @property \App\Model\Entity\ExpertAvailability $expert_availability
 * @property \App\Model\Entity\ExpertSpecializationService $expert_specialization_service
 * @property \App\Model\Entity\ExpertSpecialization $expert_specialization
 * @property \App\Model\Entity\Transaction $transaction
 * @property \App\Model\Entity\UserCard $user_card
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
        'created' => true,
        'modified' => true,
        'transaction_id' => true,
        'user_card_id' => true,
        'user' => true,
        'expert' => true,
        'expert_availability' => true,
        'expert_specialization_service' => true,
        'expert_specialization' => true,
        'transaction' => true,
        'user_card' => true
    ];
}
