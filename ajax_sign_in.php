<?php
	include 'db.php';
    $sql = "select * from user where is_show = '0' limit 1";
    $data=mysqli_query($conn,$sql);
    $data=mysqli_fetch_assoc($data);
    $id=$data['id'];
    $sql = "update user SET is_show=1 where id= $id";
    mysqli_query($conn,$sql);
    $data=json_encode($data);
    mysqli_close($conn);
    echo "$data";
?>