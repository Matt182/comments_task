<?php
namespace comments;

require_once '../vendor/autoload.php';

use comments\view\View;

$db = new DbActions();
$view = new View();

$parentId = filter_input(INPUT_POST, 'parent', FILTER_SANITIZE_STRING);
$text = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_STRING);
$nesting = filter_input(INPUT_POST, 'nesting', FILTER_SANITIZE_STRING);
$id = $db->insert($text, $parentId);
if($id == 0) return;
$comment = new Comment($db->get($id));
$view->render('post', ['child' => $comment,
                        'n' => ++$nesting]);
