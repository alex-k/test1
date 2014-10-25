<?php

class CommentModel extends Model {

    protected $storage_name="comments";
    protected $storage_create_statement= " CREATE TABLE IF NOT EXISTS comments( id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
                                            name varchar(255) DEFAULT NULL,
                                            email varchar(255) DEFAULT NULL,
                                            `text` TEXT DEFAULT NULL,
                                            `date` INT DEFAULT 0,
                                            page_id INT NOT NULL, 
                                            parent_id INT DEFAULT 0
                                            )
    " ;

    public function __construct() {
        parent::__construct();

        $name=new ModelField('name');
        $this->addField($name);
        $email=new ModelField('email');
        $this->addField($email);
        $text=new ModelField('text');
        $this->addField($text);
        $date=new ModelDateField('date');
        $this->addField($date);

        $parent_id=new ModelField('parent_id');
        $parent_id->setType(FIELDTYPE_INT);
        $this->addField($parent_id);

        $page_id=new ModelField('page_id');
        $page_id->setType(FIELDTYPE_INT);
        $this->addField($page_id);

        $replies=new ModelField('replies');
        $replies->setType(FIELDTYPE_ARRAY);
        $this->addField($replies);
    }


    public function validate() {

        $this->validated=TRUE;
        if(!strlen($this->getName())) $this->addValidateError('name',new ModelFieldErrorIsEmpty); 
        if(!strlen($this->getEmail())) $this->addValidateError('email',new ModelFieldErrorIsEmpty); 
        if(!strlen($this->getText())) $this->addValidateError('text',new ModelFieldErrorIsEmpty); 

        return $this->isValidated();

    }


    public function getName($v) { return $this->getValue('name'); }
    public function setName($v) { $this->setValue('name',$v); }

    public function getEmail($v) { return $this->getValue('email'); }
    public function setEmail($v) { $this->setValue('email',$v); }

    public function getDate($v) { return $this->getValue('date'); }
    public function setDate($v) { $this->setValue('date',$v); }

    public function getText($v) { return $this->getValue('text'); }
    public function setText($v) { $this->setValue('text',$v); }

    public function getPageID($v) { return $this->getValue('page_id'); }
    public function setPageID($v) { $this->setValue('page_id',$v); }
    public function setPage(WebpageModel $p) { $this->setPageID($p->getID()); }

    public function getReplies($v) { return $this->getValue('replies'); }
    public function setReplies($v) { $this->setValue('replies',$v); }
}
