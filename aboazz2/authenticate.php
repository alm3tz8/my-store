<?php
session_start();
include 'dbconnection.php';

$un=$_POST['username'];
$pw=$_POST['password'];
$role=$_POST['role'];

$sql="select * from users where username=:u and role=:r";
$stmt=$conn->prepare($sql);
$stmt->execute(array(':u'=>$un,':r'=>$role));
$row=$stmt->fetch();
$n=$stmt->rowCount();

if($n>0 && $row['password']==$pw){
  $_SESSION['user']=$row['username'];
  $_SESSION['role']=$row['role'];
  $_SESSION['uid']=$row['id'];

  $insert="insert into login_history(user_id,username,role)
           values(:a,:b,:c)";
  $s2=$conn->prepare($insert);
  $s2->execute(array(':a'=>$row['id'],':b'=>$row['username'],':c'=>$row['role']));

  header("Location: index1.php");
}
else{
  echo "Invalid login";
}
?>
