<?php

class WebpageService extends Service {

    public function __construct() {
        $this->repository=MysqlRepository::getInstance();
    }

    public function install() {
        $item=new WebpageModel;
        $que=$item->getStorageCreateStatement();
        $this->repository->prepare($que)->execute();
    }


    public function listAll() {

        $page = new WebpageModel;
        $this->findItems($page,"limit 100");
        $pages=$this->fetchItems($page);

        return $pages;
    }

    public function getPageByUrl($url) {
        $page=new WebpageModel;


        $this->findItems($page,"where url = :url");
        $this->repository->bind(':url',$url);
        $ret=reset($this->fetchItems($page));
        return $ret;
    }


}
