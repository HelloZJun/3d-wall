<?php
	include 'db.php';
    $sql = "select * from user where is_show = '1'";
    // 创建连接
    $conn = new mysqli($servername, $username, $password,"wall");
    $data=mysqli_query($conn,$sql);
    $data=mysqli_fetch_all($data);
    $data=json_encode($data);
    mysqli_close($conn);
    echo "$data";
?>