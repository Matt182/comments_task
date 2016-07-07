<?php
namespace comments;

require_once '../vendor/autoload.php';

use comments\view\View;
use comments\database\DbActions;

$db = new DbActions();
$view = new View();

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

if ($action == 'delete') {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
    $deleting = $db->get($id);
    $db->delete($id);
    deleteCascade($id, $db);
    $childrenOfparent = $db->getByParent($deleting['parent']);
    if (empty($childrenOfparent)) {
        $db->setNoChild($deleting['parent']);
    }


} elseif ($action == 'post') {
    $parentId = filter_input(INPUT_POST, 'parent', FILTER_SANITIZE_STRING);
    $text = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_STRING);
    $nesting = filter_input(INPUT_POST, 'nesting', FILTER_SANITIZE_STRING);
    $id = $db->insert($text, $parentId);
    if($id == 0) return;
    $comment = new Comment($db->get($id));
    $view->render('post', ['child' => $comment,
                            'n' => ++$nesting]);
}

/**
 * @param int $parentId @param DbActions $db @return void
 */
function deleteCascade($parentId, $db)
{
    $children = $db->getByParent($parentId);
    foreach ($children as $child) {
        $db->delete($child['id']);
        deleteCascade($child['id'], $db);
    }
}
