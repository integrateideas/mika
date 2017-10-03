<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ExpertCard Entity
 *
 * @property int $id
 * @property int $expert_id
 * @property string $stripe_customer_id
 * @property string $stripe_card_id
 * @property bool $status
 * @property \Cake\I18n\FrozenTime $is_deleted
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Expert $expert
 * @property \App\Model\Entity\StripeCustomer $stripe_customer
 * @property \App\Model\Entity\StripeCard $stripe_card
 */
class ExpertCard extends Entity
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
        'stripe_customer_id' => true,
        'stripe_card_id' => true,
        'status' => true,
        'is_deleted' => true,
        'created' => true,
        'modified' => true,
        'expert' => true,
        'stripe_customer' => true,
        'stripe_card' => true
    ];
}
