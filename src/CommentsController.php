<?php
namespace comments;

use comments\view\ViewInterface;
use comments\database\CommentsDbInterface;

class CommentsController
{
    private $db;
    private $view;
    private $commentsTree;

    /**
     * @param CommentsDbInterface $db @param ViewInterface $view @return void
     */
    public function __construct(CommentsDbInterface $db, ViewInterface $view)
    {
        $this->db = $db;
        $this->view = $view;
        $commentsTree = [];
    }

    /**
     * @param void @return void
     */
    public function index()
    {
        $comments = $this->db->getAll();

        $this->commentsTree = $this->buildTree($comments);

        $this->commentsTree = $this->TreeToObjects($this->commentsTree);


        $this->view->render('comments', ['commentsTree' => $this->commentsTree ? $this->commentsTree : []]);
    }

    /**
     * @param array $elements @param int $parentId @return array
     */
    function buildTree(array &$comments, $parentId = 0) {

        $branch = [];

        foreach ($comments as &$comment) {

            if ($comment['parent'] == $parentId) {
                $children = $this->buildTree($comments, $comment['id']);
                if ($children) {
                    $comment['children'] = $children;
                }
                $branch[$comment['id']] = $comment;
                unset($comment);
            }
        }
        return $branch;
    }

    /**
     * @param array $tree @return array
     */
    function TreeToObjects(array $tree)
    {
        $commentsObj = [];
        foreach ($tree as $comment) {
            $commentsObj[$comment['id']] = new Comment($comment);
            if (isset($comment['children'])) {
                $commentsObj[$comment['id']]->addChildren($this->TreeToObjects($comment['children']));
            }
        }
        return $commentsObj;
    }
}
