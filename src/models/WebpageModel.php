<?php

class WebpageModel extends Model {

    protected $storage_name="pages";
    protected $storage_create_statement= " CREATE TABLE  IF NOT EXISTS pages( id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, url varchar(255) DEFAULT NULL)" ;

    public function __construct() {
        parent::__construct();
        $url=new ModelField('url');
        $this->addField($url);
    }


    public function setUrl($value) {
        $this->setValue('url',$value);
    }
    public function getUrl() {
        return $this->getValue('url');
    }

}
