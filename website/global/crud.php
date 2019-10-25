 <?php
   require('database.php');
   session_start();

   $db = Database::getConnection();

   if(isset($_POST['register'])){
	$firstName = $_POST['FirstName'];
	$lastName = $_POST['LastName'];
	$email = $_POST['Email'];
	$password = $_POST['Password'];
	if (strlen($Password)>=6 && strlen($Password)<=60) {$pass_ok = True;}
	$password = password_hash($password, PASSWORD_DEFAULT);
		if ($pass_ok) {
			$insert=$db->prepare("INSERT INTO member SET
				FirstName=:firstName,
				LastName=:lastName,
				Email=:email,
				Password=:password
				");
	
	$check=$insert->execute(array(
		'firstName'=>$firstName,
		'lastName'=>$lastName,
		'email'=>$email,
		'password'=>$password
		));
	
			if ($check) {
				header("Location:../register.php?status=ok");
				}else{
					header("Location:../register.php?status=no");
					}
		}
			else{header("Location:../register.php?status=no");}
	}

?>
