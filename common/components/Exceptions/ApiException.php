<?php

namespace  common\components\Exceptions;

class ApiException extends BaseRuntimeException
{
	public function getName()
    {
        return 'ApiException';
    }
}

?>