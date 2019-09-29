<?php
   include('database.php');   #Do we need this?
   $mysql_conn = require('database.php');
   session_start();
   
   $user_check = $_SESSION['login_user'];
   
   $ses_sql = mysqli_query($mysql_conn,"select username from admin where username = '$user_check' ");   #TODO: Rewrite this query!
   
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
   $login_session = $row['username'];
   
   if(!isset($_SESSION['login_user'])){
      header("location:login.php");
      die();
   }
?>