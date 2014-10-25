<?php

abstract class Model {

    protected $storage_name;
    protected $storage_create_statement;
    protected $storage_id_field='id';

    protected $validated=FALSE;
    protected $validate_errors = array();

    private $fields=array();

    public function __construct() {
        $id=new ModelField($this->storage_id_field);
        $id->setType(FIELDTYPE_INT);
        $this->addField($id);

    }

    public function addField(ModelField $field) {
        $this->fields[$field->getName()]=$field;
    }
    protected function getFields() {
        return $this->fields;
    }

    public function getFieldByName($name) {
        return $this->fields[$name];
    }

    protected function setValue($name,$value) {
        $this->getFieldByName($name)->setValue($value);
    }

    public function setValues($data) {
        foreach ($this->getFields() as $f) {
            if (isset($data[$f->getName()])) $f->setValue($data[$f->getName()]);
        }
    }

    protected function getValue($name) {
        return $this->getFieldByName($name)->getValue();
    }
    public function getValues() {
        $out=array();
        foreach ($this->getFields() as $f) {
            $v=$f->getValue();

            if (is_array($v)) array_walk_recursive($v,function(&$i) { 
                    if (is_object($i) && is_a($i,'Model')) $i=$i->getValues();
                });

            $out[$f->getName()]=$v;
        }
        return $out;
    }

    public function getStorageName() {
        return $this->storage_name;
    }
    public function getStorageIdField() {
        return $this->getFieldByName($this->storage_id_field);
    }
    public function getID() {
        return $this->getStorageIdField()->getValue();
    }

    public function getStorageCreateStatement() {
        return $this->storage_create_statement;
    }



    public function __toString() {
        $out=array(get_class($this).":");
        foreach ($this->getValues() as $k=>$v) {
            $out[]=sprintf("%s => %s",$k,$v);
        }
        return implode("\n",$out);
    }


    private function getFieldsNamesArray($returnid=false) {
        $out=array();
        $idname=$this->getStorageIdField()->getName();
        foreach ($this->fields as $f) {
            $name=$f->getName();
            if (!$returnid && $name==$idname) continue;
            if ($f->getType()==FIELDTYPE_ARRAY) continue;
            $out[]=$name;
        }
        return $out;
    }

    public function getFieldsNames($returnid=false) {
        return $this->getFieldsNamesArray($returnid);
    }

    public function setID($id) {
        return $this->getStorageIdField()->setValue($id);
    }

    protected function addValidateError($field,ModelFieldError $err) {
        $this->validated=FALSE;
        $this->validate_errors[$field][]=$err;
    }

    public function getValidateErrors() {
        $out=array();
        foreach ($this->validate_errors as $f=>$arr) {
            foreach($arr as $e ) {
                $out[$f][]=get_class($e);
            }
        }
        return $out;
    }

    public function validate() {
        $this->validated=TRUE;
        return $this->isValidated();
    }
    final function isValidated() {
        return $this->validated;
    }

}

define('FIELDTYPE_STR',PDO::PARAM_STR);
define('FIELDTYPE_INT',PDO::PARAM_INT);
define('FIELDTYPE_ARRAY','FIELDTYPE_ARRAY');

class ModelField {
    private $name;
    private $value;
    private $type=FIELDTYPE_STR;
    function __construct($name) {
        $this->name=$name;
    }
    function getName() {
        return $this->name;
    }
    function setValue($value) {
        $this->value=$value;
    }
    function getValue() {
        return $this->value;
    }
    function setType($type) {
        $this->type=$type;
    }
    function getType() {
        return $this->type;
    }


}


class ModelDateField extends ModelField {
    
    function __construct($name) {
        parent::__construct($name);
        $this->setValue('now');
    }

    function setValue($value) {
        $this->value=strtotime($value);
    }
    function getValue() {
        return date(DATE_ATOM,$this->value);
    }

}


class ModelFieldError {
}

class ModelFieldErrorIsEmpty extends ModelFieldError {}
