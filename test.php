<?php
$mysql_conf = array(
    'host'    => 'localhost', 
    'db'      => 'wall', 
    'db_user' => 'root', 
    'db_pwd'  => 'mysql_zj', 
    );
$pdo = new PDO("mysql:host=" . $mysql_conf['host'] . ";dbname=" . $mysql_conf['db'], $mysql_conf['db_user'], $mysql_conf['db_pwd']);//创建一个pdo对象
$pdo->exec("set names 'utf8'");
$sql = "select * from user";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, 'joshua', PDO::PARAM_STR);
$rs = $stmt->execute();
if ($rs) {
    // PDO::FETCH_ASSOC 关联数组形式
    // PDO::FETCH_NUM 数字索引数组形式
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        var_dump($row);
    }
}

$pdo = null;//关闭连接
?>