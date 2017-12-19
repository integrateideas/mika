<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AccountDetail Entity
 *
 * @property int $id
 * @property string $account_holder_name
 * @property int $account_number
 * @property string $bank_code
 * @property string $branch_name
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class AccountDetail extends Entity
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
        'account_holder_name' => true,
        'account_number' => true,
        'bank_code' => true,
        'branch_name' => true,
        'created' => true,
        'modified' => true
    ];
}
