<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[AnswerOptions]].
 *
 * @see AnswerOptions
 */
class AnswerOptionsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return AnswerOptions[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AnswerOptions|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
