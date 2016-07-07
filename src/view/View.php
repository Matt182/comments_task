<?php
namespace comments\view;

use comments\view\ViewInterface;

class View implements ViewInterface
{
    /**
     * @param string $page @param array $args @return void
     */
    public function render($page, $args)
    {
        extract($args);
        include_once "html/$page.php";
    }
}
