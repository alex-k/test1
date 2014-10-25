<?php

abstract class Service {
    protected $repository;

    public function submitNewItem(Model $item) {

            if (! $item->validate() ) {
                throw new ModelValidateException('can not validate model');
            }

            $this->repository->insertModel($item);
            $this->repository->bindModel($item);
            $this->repository->execute();
            $item->setID($this->repository->id());
    }

    abstract public function install(); 

    public function findItems(Model $item,$where="") {
        $this->repository->selectModel($item,$where);
    }
    public function fetchItems(Model $m) {
        $out=array();
        foreach ($this->repository->fetch() as $data) {
            $classname=get_class($m);
            $item = new $classname;
            $item->setValues($data);
            $out[]=$item;
        }

        return $out;

    }

}
