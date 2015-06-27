<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Access]].
 *
 * @see Access
 */
class AccessQuery extends \yii\db\ActiveQuery
{
    public function allowed()
    {
        $this->andWhere('[[busy]]=0');
        return $this;
    }

    public function busy()
    {
        $this->andWhere('[[busy]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return Access[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Access|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}