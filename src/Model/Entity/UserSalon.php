<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserSalon Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $salon_name
 * @property string $location
 * @property bool $status
 * @property \Cake\I18n\FrozenTime $is_deleted
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $zipcode
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\AccountDetail[] $account_details
 * @property \App\Model\Entity\Expert[] $experts
 */
class UserSalon extends Entity
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
        'salon_name' => true,
        'location' => true,
        'status' => true,
        'is_deleted' => true,
        'created' => true,
        'modified' => true,
        'zipcode' => true,
        'user' => true,
        'account_details' => true,
        'experts' => true
    ];
}
