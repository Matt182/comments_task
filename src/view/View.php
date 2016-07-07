<?php
namespace comments\view;

use comments\view\ViewInterface;

class View implements ViewInterface
{
    public function render($page, $args)
    {
        extract($args);
        include_once "html/$page.php";
    }
}
