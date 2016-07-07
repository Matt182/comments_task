<?php
namespace comments;

require_once '../vendor/autoload.php';

use comments\view\View;

$db = new DbActions();
$view = new View();

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
$db->delete($id);
deleteCascade($id, $db);

function deleteCascade($parentId, $db)
{
    $children = $db->getByParent($parentId);
    foreach ($children as $child) {
        $db->delete($child['id']);
        deleteCascade($child['id'], $db);
    }
}
