<?php
namespace comments\database;

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

    /**
     * @param int $id @return array
     */
    public function get($id)
    {
        $stp = $this->connection->prepare('select * from comment where id =:id');
        $stp->bindParam(':id', $id, PDO::PARAM_INT);
        $stp->execute();
        if (!$stp) {
            return [];
        }
        $row = $stp->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    /**
     * @param int $parentId @return array
     */
    public function getByParent($parentId)
    {
        $stp = $this->connection->prepare('select * from comment where parent =:id order by created desc');
        $stp->bindParam(':id', $parentId, PDO::PARAM_INT);
        $stp->execute();

        if (!$stp) {
            return [];
        }
        $rows = $stp->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    /**
     * @param string $text @param int $parentId @return int
     */
    public function insert($text, $parentId)
    {
        try {
            $this->connection->beginTransaction();
            $stp = $this->connection->prepare("insert into comment (text, parent) values (:text, :id)");
            $stp->bindParam(':text', $text, PDO::PARAM_STR);
            $stp->bindParam(':id', $parentId, PDO::PARAM_INT);
            $stp->execute();
            if(!$stp) throw new Exception("Error inserting DB", 1);
            $insertedId = $this->connection->lastInsertId();
            $stp = $this->connection->prepare("update comment set has_child='1' where id=:id");
            $stp->bindParam(':id', $parentId, PDO::PARAM_INT);
            $stp->execute();
            if(!$stp) throw new Exception("Error updating parent", 1);
            $this->connection->commit();
            return $insertedId;
        } catch (Exception $e) {
            $this->connection->rollBack();
            // log exception
            return 0;
        }
    }

    /**
     * @param int $id @return boolval
     */
    public function delete($id)
    {
        $stp = $this->connection->prepare("delete from comment where id =:id");
        $stp->bindParam(':id', $id, PDO::PARAM_INT);
        $stp->execute();
        return $stmt;
    }

    /**
     * @param int $id @return void
     */
    public function setNoChild($id)
    {
        $stp = $this->connection->prepare("update comment set has_child='0' where id =:id");
        $stp->bindParam(':id', $id, PDO::PARAM_INT);
        $stp->execute();
    }
}
