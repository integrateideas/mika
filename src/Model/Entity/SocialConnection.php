<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SocialConnection Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $fb_identifier
 * @property bool $status
 * @property bool $is_deleted
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 */
class SocialConnection extends Entity
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
        'fb_identifier' => true,
        'status' => true,
        'is_deleted' => true,
        'created' => true,
        'modified' => true,
        'user' => true
    ];
}
