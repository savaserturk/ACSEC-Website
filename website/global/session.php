<?php
   require('database.php');
   session_start();

   $db = Database::getConnection();

   $user_check = $_SESSION['login_user'];

   $query = $db->prepare('SELECT MemberId FROM Member WHERE MemberId = ?');
   $query->execute([$user_check]);

   if ($query->rowCount() < 1) {
      header("location:login.php");
      die();
   }
?>
