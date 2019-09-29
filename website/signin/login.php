 <?php
   #include("database.php");
   $mysql_conn = require('database.php');
   session_start();
  
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
      $myusername = mysqli_real_escape_string($mysql_conn,$_POST['email']);
      $mypassword = mysqli_real_escape_string($mysql_conn,$_POST['password']);
      
      $sql = "SELECT id FROM admin WHERE username = '$myusername' and passcode = '$mypassword'";   #TODO: Rewrite this query!
      $result = mysqli_query($mysql_conn,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $active = $row['active'];
      
      $count = mysqli_num_rows($result);
      
      // If result matched $myusername and $mypassword, table row must be 1 row
		
      if($count == 1) {
         session_register("myusername");
         $_SESSION['login_user'] = $myusername;
         
         header("location: welcome.php");    #Find out WTF this is
      }else {
         $error = "Your Login Name or Password is invalid!";
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