<?php
namespace comments;

/**
 *
 */
class Comment
{
    private $id;
    private $text;
    private $date;
    private $parent;
    private $has_child;
    private $children;

    function __construct(array $commentRow)
    {
        $this->id = $commentRow['id'];
        $this->text = $commentRow['text'];
        $this->date = $commentRow['created'];
        $this->parent = $commentRow['parent'];
        $this->has_child = $commentRow['has_child'];
        $this->children = array();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function hasChild()
    {
        return $this->has_child;
    }

    public function addChildren(array $children)
    {
        $this->children = $children;
    }

    public function getChildren()
    {
        return $this->children;
    }
}
