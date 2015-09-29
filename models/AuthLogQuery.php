<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AuthLog]].
 *
 * @see AuthLog
 */
class AuthLogQuery extends \yii\db\ActiveQuery
{
    public function user()
    {
        $this->andWhere(['user_id'=>\Yii::$app->user->id]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return AuthLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AuthLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


}