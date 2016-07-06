<?php
namespace comments;

require_once 'vendor/autoload.php';

use comments\CommentsController;
use comments\DbActions;

$commentsController = new CommentsController(new DbActions());
print_r($commentsController->index());
