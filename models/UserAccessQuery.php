<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserAccess]].
 *
 * @see UserAccess
 */
class UserAccessQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return UserAccess[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserAccess|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}