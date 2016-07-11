<?php
namespace comments\database;

interface CommentsDbInterface
{
    public function get($id);
    public function getAll();
    public function delete($id);
    public function setNoChild($id);
}
?>
