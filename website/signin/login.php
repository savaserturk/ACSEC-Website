<?php
   require('../global/database.php');

   $db = Database::getConnection();

   if($_SERVER["REQUEST_METHOD"] == "POST") {

      // username and password sent from form
      $username = $_POST['email'];
      $password = $_POST['password'];
      $query1 = $db->prepare('SELECT Password, MemberId FROM Member WHERE Email = ?');
      $query1->execute([$username]);
      $count = $query1->rowCount();
      $row = $query1->fetch();
      $password_h = $row['Password'];

      // If result matched $myusername and $mypassword, table row must be 1 row
      if($count == 1 && password_verify($password, $password_h)) {
        session_start();
         $_SESSION['username'] = $username;

         header("Location: index.html");
      }else {
         $error = "Your Login Name or Password is invalid!";
         echo $error;
      }
   }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Sign In</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <h1>Sign In</h1>
    <form action="<?PHP $_SERVER['PHP_SELF']; ?>" method="post">
      <div>
        <span class="label">email: </span>
        <input type="text" name="email" value="">
      </div>
      <div>
        <span class="label">password: </span>
        <input type="password" name="password" value="">
      </div>
      <div>
        <input type="submit" name="submit" value="Submit">
      </div>
    </form>
  </body>
</html>
