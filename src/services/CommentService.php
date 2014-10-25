<?php

class CommentService extends Service {

    public function __construct() {
        $this->repository=MysqlRepository::getInstance();
    }

    public function install() {
        $item=new CommentModel;
        $que=$item->getStorageCreateStatement();
        var_dump($que);
        $this->repository->prepare($que)->execute();
    }
    
    public function getCommentsForPage(WebpageModel $page) {

        $comment = new CommentModel;
        $this->findItems($comment,"where parent_id=0 and  page_id = :page_id");
        $this->repository->bind(':page_id',$page->getID());
        $comments=$this->fetchItems($comment);
        $this->getRepliesForCommentsRecursive($comments);
        return $comments;

    }

    public function getRepliesForCommentsRecursive($comments) {
        foreach ($comments as $c) {
            $c->setReplies($this->getRepliesForComment($c));
            $this->getRepliesForCommentsRecursive($c->getReplies());
        }
    }

    public function getRepliesForComment(CommentModel $comment) {

        $this->findItems($comment,"where parent_id=:parent_id");
        $this->repository->bind(':parent_id',$comment->getID());
        $comments=$this->fetchItems($comment);
        return $comments;

    }





}
