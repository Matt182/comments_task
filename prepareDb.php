<?php
namespace comments;

require_once "vendor/autoload.php";

use PDO;

$dbname = getenv('dbname');
$dsn = getenv('driver') . ":dbname=" . $dbname . ";host=" . getenv('host');
$dbusername = getenv('username');
$dbpass = getenv('pass');

$pdo = new PDO($dsn, $dbusername, $dbpass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS comment (id INT PRIMARY KEY AUTO_INCREMENT,
        text TEXT NOT NULL, created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        parent int DEFAULT 0, has_child TINYINT DEFAULT 0) ENGINE = InnoDB;");
    echo "DB prepared";
} catch (Exception $e) {
    echo $e->getMessage();
}
