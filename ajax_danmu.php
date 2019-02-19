<?php
    include 'db.php';
    $id= $_POST['id'];
    $time=time();
    $time=$time-10;
    if($id&&$id!==0){
        $sql = "select * from text where id>$id limit 1";
    }else{
        $sql = "select * from text where time>$time limit 1";
    }
    $data=mysqli_query($conn,$sql);
    $data=mysqli_fetch_assoc($data);
    $data=json_encode($data);
    mysqli_close($conn);
    echo "$data";
?>