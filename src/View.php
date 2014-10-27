<?php

define('RESPONSE_OK',200);
define('RESPONSE_CREATED',201);
define('RESPONSE_BADREQUEST',400);
define('RESPONSE_NOTFOUND',404);
define('RESPONSE_NOTACCEPTABLE',406);
define('RESPONSE_SERVERERROR',500);
define('RESPONSE_NOTIMPLEMENTED',501);

abstract class View{
    private $message="";
    protected $viewname;
    private $data=array();
    protected $code=RESPONSE_OK;


    public function setData($key,$data) {
        $this->data[$key]=$data;
    }
    public function setCode($code) { $this->code=$code;}
    public function setMessage($message) { $this->message=$message;}

    public function getMessage() { return $this->message; }
    public function getViewName() { return $this->viewname; }
    public function getData() { 
        if (func_num_args()===0) return $this->data;
        $key=func_get_arg(0);
        return array_key_exists($key,$this->data) ? $this->data[$key] : array(); 
    }
    public function getCode() { return $this->code; }
}
