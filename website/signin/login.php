 <?php
   require('../global/database.php');
   session_start();

   $db = Database::getConnection();

   if($_SERVER["REQUEST_METHOD"] == "POST") {

      // username and password sent from form
      $username = $_POST['email'];
      $password = $_POST['password'];
      $query1 = $db->prepare('SELECT Password FROM Member WHERE Email = ?');
      $password_h = $query1->execute([$username]);

      $query2 = $db->prepare('SELECT MemberId FROM Member WHERE Email = ?');
      $result = $query2->execute([$username]);
      $row = $result->fetch();

      $count = $result->rowCount();

      // If result matched $myusername and $mypassword, table row must be 1 row
      if($count == 1 && password_verify($password, $password_h)) {
         session_register("username");
         $_SESSION['username'] = $username;

         header("location: welcome.php");
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
  </body>
</html>
