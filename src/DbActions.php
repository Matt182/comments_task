<?php
namespace comments;

use PDO;

class DbActions implements CommentsDbInterface
{
    private $connection;

    function __construct()
    {
        $dbname = getenv('dbname');
        $dsn = getenv('driver') . ":dbname=" . $dbname . ";host=" . getenv('host');
        $dbusername = getenv('username');
        $dbpass = getenv('pass');
        try{
        $this->connection = new PDO($dsn, $dbusername, $dbpass);
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function get($id)
    {
        $stmt = $this->connection->query("select * from comment where id ='$id'");
        if (!$stmt) {
            return [];
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    public function getByParent($parentId)
    {
        $stmt = $this->connection->query("select * from comment where parent ='$parentId'");
        if (!$stmt) {
            return [];
        }
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function delete($id)
    {
        $stmt = $this->connection->exec("delete from comment where id ='$id'");
        return $stmt;
    }
}
