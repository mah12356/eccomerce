<?php

namespace  common\components\Exceptions;

class HttpException extends BaseRuntimeException
{
	public function getName()
    {
        return 'HttpException';
    }	
}

?>