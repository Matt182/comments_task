<?php
namespace comments;

class CommentsController
{
    private $db;
    private $commentsTree;

    public function __construct(CommentsDbInterface $db)
    {
        $this->db = $db;
        $commentsTree = array();
    }

    public function index()
    {
        $comments = $this->db->getByParent(0);
        foreach ($comments as $comment) {
            $comment = new Comment($comment);
            $this->commentsTree[] = $comment;
            if($comment->hasChild()) {
                $comment->addChildren($this->getChildren($comment->getId()));
            }
        }
        include 'comments.php';
    }

    public function getChildren($parentId)
    {
        $children = array();
        $comments = $this->db->getByParent($parentId);
        foreach ($comments as $comment) {
            $comment = new Comment($comment);
            if($comment->hasChild()) {
                $comment->addChildren($this->getChildren($comment->getId()));
            }
            $children[] = $comment;
        }
        return $children;
    }
}
