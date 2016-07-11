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
     * @param void @return array
     */
    public function getAll()
    {
        $stp = $this->connection->prepare('select * from comment order by created desc');
        $stp->bindParam(':id', $parentId, PDO::PARAM_INT);
        $stp->execute();

        return $row = $stp->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $text @param int $parentId @return int
     */
    public function insert($text, $parentId)
    {
        try {
            $this->connection->beginTransaction();
            if ($parentId == 0) {
                $parentLeft = 0;
            } else {
                $stp = $this->connection->prepare('select * from comment where id =:id');
                $stp->bindParam(':id', $parentId, PDO::PARAM_INT);
                $stp->execute();
                if(!$stp) throw new Exception("Error inserting into DB", 1);


                $parentRow = $stp->fetch(PDO::FETCH_ASSOC);
                $parentLeft = $parentRow['lft'];
            }
            $stp = $this->connection->prepare('update comment set rgt = rgt + 2 where rgt > :parentLeft');
            $stp->bindParam(':parentLeft', $parentLeft, PDO::PARAM_INT);
            $stp->execute();
            if(!$stp) throw new Exception("Error inserting into DB", 1);

            $stp = $this->connection->prepare('update comment set lft = lft + 2 where lft > :parentLeft');
            $stp->bindParam(':parentLeft', $parentLeft, PDO::PARAM_INT);
            $stp->execute();
            if(!$stp) throw new Exception("Error inserting into DB", 1);

            $stp = $this->connection->prepare('insert into comment (text, parent, lft, rgt) values (:text, :parentId, :lft, :rgt)');
            $stp->bindParam(':text', $text, PDO::PARAM_STR);
            $stp->bindParam(':parentId', $parentId, PDO::PARAM_INT);
            $stp->bindParam(':lft', ++$parentLeft, PDO::PARAM_INT);
            $stp->bindParam(':rgt', ++$parentLeft, PDO::PARAM_INT);
            $stp->execute();

            if(!$stp) throw new Exception("Error inserting into DB", 1);
            $insertedId = $this->connection->lastInsertId();
            $stp = $this->connection->prepare("update comment set has_child='1' where id=:id");
            $stp->bindParam(':id', $parentId, PDO::PARAM_INT);
            $stp->execute();

            if(!$stp) throw new Exception("Error inserting into DB", 1);
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
        try {
            $this->connection->beginTransaction();
            $stp = $this->connection->prepare("select * from comment where id =:id");
            $stp->bindParam(':id', $id, PDO::PARAM_INT);
            $stp->execute();
            if(!$stp) throw new Exception("Error when deleting id: $id", 1);

            $deleting = $stp->fetch(PDO::FETCH_ASSOC);
            $deletingLeft = $deleting['lft'];
            $deletingRight = $deleting['rgt'];
            $width = $deletingRight - $deletingLeft + 1;

            $stp = $this->connection->prepare("delete from comment where lft >= :lft and lft <= :rgt ");
            $stp->bindParam(':lft', $deletingLeft, PDO::PARAM_INT);
            $stp->bindParam(':rgt', $deletingRight, PDO::PARAM_INT);
            $stp->execute();
            if(!$stp) throw new Exception("Error when deleting id: $id", 1);

            $stp = $this->connection->prepare("update comment set rgt = rgt - :width where rgt > :rgt ");
            $stp->bindParam(':width', $width, PDO::PARAM_INT);
            $stp->bindParam(':rgt', $deletingRight, PDO::PARAM_INT);
            if(!$stp) throw new Exception("Error when deleting id: $id", 1);

            $stp = $this->connection->prepare("update comment set lft = lft - :width where lft > :rgt ");
            $stp->bindParam(':width', $width, PDO::PARAM_INT);
            $stp->bindParam(':rgt', $deletingRight, PDO::PARAM_INT);
            if(!$stp) throw new Exception("Error when deleting id: $id", 1);
            $this->connection->commit();

            return $stp;
        } catch (Exception $e) {
            //log Exception
            $this->connection->rollBack();
            return $stp;
        }

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
