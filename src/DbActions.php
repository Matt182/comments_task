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
        $stmt = $this->connection->query("select * from comment where parent ='$parentId' order by created desc");

        if (!$stmt) {
            return [];
        }
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function insert($text, $parentId)
    {
        $this->connection->exec("insert into comment (text, parent) values ('$text', '$parentId')");
        $insertedId = $this->connection->lastInsertId();
        $this->connection->exec("update comment set has_child='1' where id='$parentId'");
        return $insertedId;
    }

    public function delete($id)
    {
        $stmt = $this->connection->exec("delete from comment where id ='$id'");
        return $stmt;
    }
}
