<?php
namespace comments;

require_once 'vendor/autoload.php';

use comments\CommentsController;
use comments\database\DbActions;
use comments\view\View;

$commentsController = new CommentsController(new DbActions(), new View());
$commentsController->index();
