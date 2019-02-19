<?php
	$servername = "192.168.61.129";
    $username = "root";
    $password = "mysql_zj";
    // 创建连接
    $conn = new mysqli($servername, $username, $password,"wall");
    $sql = "select * from user where is_show = '1'";
    $data=mysqli_query($conn,$sql);

    mysqli_close($conn);
    echo "$data";
?>