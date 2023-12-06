<?php

namespace common\components;

use yii\base\Component;

class PublicData extends Component
{
    const LAYOUT_BLANK = 'blank';
    const FRONT_LAYOUT_USER = 'user';
    const FRONT_LAYOUT_CONSTRUCTOR = 'constructor';

    const BACK_LAYOUT_MANAGER = 'main';
    const BACK_LAYOUT_ADMIN = 'admin';
    const BACK_LAYOUT_INSPECTOR = 'inspector';
    const BACK_LAYOUT_FINANCE = 'finance';
}