<?php
namespace comments;

require_once '../vendor/autoload.php';

use comments\view\View;

$db = new DbActions();
$view = new View();

$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
$deleting = $db->get($id);
$db->delete($id);
deleteCascade($id, $db);
$childrenOfparent = $db->getByParent($deleting['parent']);
if (empty($childrenOfparent)) {
    $db->setNoChild($deleting['parent']);
}

function deleteCascade($parentId, $db)
{
    $children = $db->getByParent($parentId);
    foreach ($children as $child) {
        $db->delete($child['id']);
        deleteCascade($child['id'], $db);
    }
}
