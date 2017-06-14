<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Expense Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $description
 * @property int $user_id
 * @property int $category_id
 * @property float $value
 * @property \Cake\I18n\FrozenDate $date
 * @property string $remark
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Category $category
 */
class Expense extends Entity
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
        '*' => true,
        'id' => false
    ];
}
