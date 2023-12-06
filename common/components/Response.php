<?php

namespace common\components;

use yii\base\Component;

class Response extends Component
{
    public $i = 0;
    public $result = true;
    public $data = null;
    public $input = null;
    public $output = null;
    public $model = [];
    public $errors = [];
    public $message = [];


    public function setI()
    {
        $this->i += 1;
    }

    /**
     * @return bool
     */
    public function isResult()
    {
        return $this->result;
    }

    /**
     * @param bool $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param null $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return null
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @param null $input
     */
    public function setInput($input)
    {
        $this->input = $input;
    }

    /**
     * @return null
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param null $output
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }

    /**
     * @return array
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param array $model
     */
    public function setModel($model)
    {
        $this->model[$this->i] = $model;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param string $error
     */
    public function setErrors($error)
    {
        $this->errors[$this->i] = $error;
    }

    /**
     * @return array
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message[$this->i] = $message;
    }

    public function notSet($name)
    {
        return ' * ' . $name . ' * not set';
    }

    public function nullSet($name)
    {
        return ' table ' . $name . ' * for this data is null';
    }

    public function notValidate($name)
    {
        return ' * ' . $name . ' * not Validate';
    }

    public function denyAccess()
    {
        return ' this user for action not access';
    }

    public function checkNull($filed, $message)
    {
        if (!isset($this->model->$filed) || !$this->model->$filed) {
            $this->setMessage($message);
            $this->setErrors($this->notSet($filed));
            $this->setI();
        }
    }
}