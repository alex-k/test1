<?php
define('REQUEST_GET','get');
define('REQUEST_POST','post');
define('REQUEST_PUT','put');
define('REQUEST_DELETE','delete');

define('FORMAT_JSON','json');
define('FORMAT_HTML','html');


class Request {
    public $method=REQUEST_GET;
    public $path=array();
    public $data=array();
    public $format;
    public $output_format;

    public function __construct() {
        if (strtoupper($_SERVER['REQUEST_METHOD'])=='POST') $this->method=REQUEST_POST;
        else if (strtoupper($_SERVER['REQUEST_METHOD'])=='PUT') $this->method=REQUEST_PUT;
        else if (strtoupper($_SERVER['REQUEST_METHOD'])=='DELETE') $this->method=REQUEST_DELETE;


        /*
        $url=parse_url($_SERVER['REQUEST_URI']);
        $path=$url['path'];
        */

        $path=parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
        $ext=pathinfo($path,PATHINFO_EXTENSION);

        if ($ext) {
            $path=preg_replace('/\.'.$ext.'$/','',$path);
            $ext=strtolower($ext);
        }


        $this->path=explode('/', $path);

        $this->parseData();
        
        if (strpos($_SERVER['HTTP_ACCEPT'],'text/html')!==FALSE) $this->output_format=FORMAT_HTML;

        if (strtolower($ext)=='htm') $this->output_format=FORMAT_HTML;
        if (strtolower($ext)=='html') $this->output_format=FORMAT_HTML;
        if (strtolower($ext)=='json') $this->output_format=FORMAT_JSON;

        if (stripos($_SERVER['HTTP_ACCEPT'],'text/json')!==FALSE) $this->output_format=FORMAT_JSON;
        if (stripos($_SERVER['HTTP_ACCEPT'],'application/json')!==FALSE) $this->output_format=FORMAT_JSON;
        if (stripos($_SERVER['HTTP_CONTENT_TYPE'],'application/json')!==FALSE) $this->output_format=FORMAT_JSON;


    }   
    public function getOutputFormatName() {
        return $this->output_format;
    }
    public function getFormatName() {
        return $this->format;
    }
    public function getMethodName() {
        return $this->method;
    }
    public function getPath() {
        if (count($this->path)==2 && empty($this->path[1])) return 'index';
        return isset($this->path[1]) ? $this->path[1] : '';
    }
    private function parseData() {
        $data=array();
        if (isset($_SERVER['QUERY_STRING']))  parse_str($_SERVER['QUERY_STRING'], $data);

        $input = file_get_contents("php://input");
        switch(strtolower(reset(explode(';',$_SERVER['CONTENT_TYPE'])))) {
            case 'application/json':
                $this->format=FORMAT_JSON;
                if (($vars=json_decode($input,TRUE))) $data=array_merge($data,$vars);
                break;
                
            case 'application/x-www-form-urlencoded':
                $this->format=FORMAT_HTML;
                $vars=array();
                parse_str($input,$vars);
                $data=array_merge($data,$vars);
                break;
        }

        $this->data=$data;
    }

    public function getData() { 
        if (func_num_args()===0) return $this->data;
        $key=func_get_arg(0);
        return $this->data[$key]; 
    }
}
