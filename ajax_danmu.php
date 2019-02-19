<?php
    $id= $_POST['id'];
	$servername = "localhost";
    $username = "wall";
    $password = "walladmin";
    $time=time();
    $time=$time-10;
    if($id&&$id!==0){
        $sql = "select * from text where id>$id limit 1";
    }else{
        $sql = "select * from text where time>$time limit 1";
    }
    // 创建连接
    $conn = new mysqli($servername, $username, $password,"wall");  
    $data=mysqli_query($conn,$sql);
    $data=mysqli_fetch_assoc($data);
    mysqli_query($conn,$sql);
    $data=json_encode($data);
    mysqli_close($conn);
    echo "$data";
?>