<?php


namespace App;



class SYMBResponse
{


    public $status;
    public $message;
    public $title;
    public $type;
    public $data;
    public $httpStatusCode;

    public function __construct($status, $message, $type, $data,$statusCode=200,$title=''){
        $this->setStatus($status);
        $this->setMessage($message);
        $this->setType($type);
        $this->setData($data);
        $this->setHttpStatusCode($statusCode);
        $this->setTitle($title);
    }

    // getters and setters

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    // custom functions
    public static function getNotExistMessage($message, $data, $statusCode=404,$title='Sorry!'){
        return new self(false, $message, SYMBConstants::ERROR_MESSAGE, $data,$statusCode,($title)?$title:'Sorry!');
    }

    public  static function getCustomerResponse($message,$data,$statusCode,$title='Ok!')
    {
        return new self(false, $message, SYMBConstants::ERROR_MESSAGE, $data,$statusCode,($title)?$title:'Ok!');
    }

    public static function getErrorMessage($message, $data, $statusCode=200,$title='Error!'){
        return new self(false, $message, SYMBConstants::ERROR_MESSAGE, $data,$statusCode,($title)?$title:'Error!');
    }

    public static function getSuccessMessage($message, $data, $statusCode=200,$title='Thank You!'){
        return new self(true, $message, SYMBConstants::SUCCESS_MESSAGE, $data,$statusCode,($title)?$title:'Thank You!');
    }

    public static function getInfoMessage($message, $data, $statusCode=200,$title='Hi!'){
        return new self(true, $message, SYMBConstants::INFO_MESSAGE, $data,$statusCode,($title)?$title:'Hi!');
    }

    public static function getWarningMessage($message, $data, $statusCode=200,$title='Hi!'){
        return new self(false, $message, SYMBConstants::WARNING_MESSAGE, $data,$statusCode,($title)?$title:'Hi!');
    }

    /**
     * @return mixed
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * @param mixed $httpStatusCode
     */
    public function setHttpStatusCode($httpStatusCode)
    {
        $this->httpStatusCode = $httpStatusCode;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }





}
