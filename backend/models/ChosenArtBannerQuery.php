<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[ChosenArtBanner]].
 *
 * @see ChosenArtBanner
 */
class ChosenArtBannerQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ChosenArtBanner[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ChosenArtBanner|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
