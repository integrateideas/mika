<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Expert Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $user_salon_id
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\UserSalon $user_salon
 * @property \App\Model\Entity\Availability[] $availabilities
 * @property \App\Model\Entity\ExpertCard[] $expert_cards
 * @property \App\Model\Entity\ExpertLocation[] $expert_locations
 * @property \App\Model\Entity\ExpertSpecializationService[] $expert_specialization_services
 * @property \App\Model\Entity\ExpertSpecialization[] $expert_specializations
 */
class Expert extends Entity
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
        'user_salon_id' => true,
        'user' => true,
        'user_salon' => true,
        'availabilities' => true,
        'expert_cards' => true,
        'expert_locations' => true,
        'expert_specialization_services' => true,
        'expert_specializations' => true
    ];
}
