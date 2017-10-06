<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ExpertAvailability Entity
 *
 * @property int $id
 * @property int $expert_id
 * @property \Cake\I18n\FrozenTime $available_from
 * @property \Cake\I18n\FrozenTime $available_to
 * @property bool $overlapping_allowed
 * @property bool $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Expert $expert
 */
class ExpertAvailability extends Entity
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
        'available_from' => true,
        'available_to' => true,
        'overlapping_allowed' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'expert' => true
    ];
}
