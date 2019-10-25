<?php
   require('database.php');
   session_start();

   $db = Database::getConnection();

   $user_check = $_SESSION['username'];

   $query = $db->prepare('SELECT MemberId FROM Member WHERE MemberId = ?');
   $result = $query->execute([$user_check]);
   $row = $result->fetch();

   $login_session = $row['username'];  # need this variable because it can be used in any scripts that include 'session.php' (as session name)

   if (!isset($_SESSION['username'])) {     # this is the logic to check whether a session is active. if not, go back to login page.
      header("location:login.php");
      die();
   }
?>
