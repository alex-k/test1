<?php


abstract class Repository {
    static function getInstance()
    {  
        static $instance;
        if (!isset($instance)) {
            $classname=get_called_class();
            $instance = new $classname();
        }
        return $instance;
    }

    private $dbh;
    private $error;

    private $sth;

    private $queryFields=array();

    function __construct() {
        $this->dbh= new PDO($this->dsn,$this->user,$this->password,$this->options);
        $this->dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }

    public function prepare($query){
        $this->sth = $this->dbh->prepare($query);
        return $this;
    }

    public function bind($param, $value, $type = null) {
        if ($type===null) {
            $type = PDO::PARAM_STR;
            if (is_null($value)) $type = PDO::PARAM_NULL;
            if (is_bool($value)) $type = PDO::PARAM_BOOL;
            if (is_int($value)) $type = PDO::PARAM_INT;
        }
        $this->sth->bindValue($param, $value, $type);
    }

    public function execute(){
        try {
            $this->sth->execute();
        } catch (PDOException $e) {
            if ($e->getCode()=='42S02') throw new RepositoryTableNotExistsException($e->getMessage());
            throw new RepositoryException($e->getMessage());
        }
        return $this;
    }

    private function escapeFieldsNames($arr) {
        return implode(',',array_map(function($v){
            return '`'.$v.'`';
        },$arr));
    }
    private function getPlaceholders($arr) {
        return implode(',',array_map(function($v){
            return ':'.$v;
        },$arr));
    }

    public function selectModel(Model $m, $w="") {
        $this->parseModel($m);
        $this->queryFieldsStr = $this->escapeFieldsNames($m->getFieldsNames(TRUE));
        $que=sprintf("SELECT %s from `%s` %s", $this->queryFieldsStr, $this->queryTable, $w);
        $this->prepare($que);
        return $this;
    }

    public function insertModel(Model $m) {
        $this->parseModel($m);
        $que=sprintf("INSERT INTO `%s` (%s) VALUES (%s)", $this->queryTable, $this->queryFieldsStr,$this->queryPlaceholdersStr);
        $this->prepare($que);
        return $this;
    }

    private function parseModel(Model $m) {
        $this->queryTable=$m->getStorageName();
        $this->queryFields = $m->getFieldsNames();
        $this->queryPlaceholdersStr=$this->getPlaceholders($this->queryFields);
        $this->queryFieldsStr= $this->escapeFieldsNames($this->queryFields);
    }


    public function bindModel(Model $m) {

        foreach ($this->queryFields as $name) {
            $f=$m->getFieldByName($name);
            $param=':'.$f->getName();
            $this->sth->bindValue($param, $f->getValue(), $f->getType());
        }

        return $this;

    }



    public function error() {
        $err=$this->sth->errorInfo();
        if ($err[0]==='00000') return false;
        return $err;
    }

    public function fetch(){
        $this->execute();
        return $this->sth->fetchAll(PDO::FETCH_ASSOC);
    }
    public function first(){
        $this->execute();
        return $this->sth->fetch(PDO::FETCH_ASSOC);
    }
    public function count(){
            return $this->sth->rowCount();
    }
    public function id(){
            return $this->dbh->lastInsertId();
    }

}

