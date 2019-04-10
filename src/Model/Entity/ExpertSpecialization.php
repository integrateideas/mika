<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ExpertSpecialization Entity
 *
 * @property int $id
 * @property int $expert_id
 * @property int $specialization_id
 * @property string $description
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Expert $expert
 * @property \App\Model\Entity\Specialization $specialization
 * @property \App\Model\Entity\ExpertSpecializationService[] $expert_specialization_services
 */
class ExpertSpecialization extends Entity
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
        'specialization_id' => true,
        'description' => true,
        'created' => true,
        'modified' => true,
        'expert' => true,
        'specialization' => true,
        'expert_specialization_services' => true
    ];
}
