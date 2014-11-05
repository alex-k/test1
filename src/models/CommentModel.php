<?php

class CommentModel extends Model {

    protected $storage_name="comments";
    protected $storage_create_statement= " CREATE TABLE IF NOT EXISTS comments( id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
                                            name varchar(255) DEFAULT NULL,
                                            email varchar(255) DEFAULT NULL,
                                            `text` TEXT DEFAULT NULL,
                                            `date` INT DEFAULT 0,
                                            `verified` INT NOT NULL DEFAULT 0,
                                            page_id INT NOT NULL, 
                                            parent_id INT DEFAULT 0
                                            )
    " ;

    public function __construct() {
        parent::__construct();

        $this->addField(new ModelField('name'));
        $this->addField(new ModelField('email'));
        $this->addField(new ModelField('text'));
        $this->addField(new ModelIntField('verified'));
        $this->addField(new ModelDateField('date'));


        $this->addField(new ModelIntField('parent_id'));
        $this->addField(new ModelIntField('page_id'));
        $this->addField(new ModelSetField('replies'));

    }


    public function validate() {

        $this->validated=TRUE;
        if(!strlen($this->getName())) $this->addValidateError('name',new ModelFieldErrorIsEmpty); 
        if(!strlen($this->getEmail())) $this->addValidateError('email',new ModelFieldErrorIsEmpty); 
        if(!strlen($this->getText())) $this->addValidateError('text',new ModelFieldErrorIsEmpty); 

        return $this->isValidated();

    }


    public function getName() { return $this->getValue('name'); }
    public function setName($v) { $this->setValue('name',$v); }

    public function getEmail() { return $this->getValue('email'); }
    public function setEmail($v) { $this->setValue('email',$v); }

    public function getDate() { return $this->getValue('date'); }
    public function setDate($v) { $this->setValue('date',$v); }

    public function getText() { return $this->getValue('text'); }
    public function setText($v) { $this->setValue('text',$v); }

    public function getPageID() { return $this->getValue('page_id'); }
    public function setPageID($v) { $this->setValue('page_id',$v); }
    public function setPage(WebpageModel $p) { $this->setPageID($p->getID()); }

    public function getReplies() { return $this->getValue('replies'); }
    public function setReplies($v) { $this->setValue('replies',$v); }
}
