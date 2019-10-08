if(isset($_POST['register'])){
	$FirstName=$_POST['FirstName'];
	$LastName=$_POST['LastName'];
	$Email=$_POST['Email'];
	$Password=$_POST['Password'];
		if (strlen($Password)>=6 && strlen($Password)<=60) {
	$insert=$db->prepare("INSERT INTO member SET
		FirstName=:FirstName,
		LastName=:LastName,
		Email=:Email,
		Password=:Password
	");
	$check=$insert->execute(array(
		'FirstName'=>$_POST['FirstName'],
		'LastName'=>$_POST['LastName'],
		'Email'=>$_POST['Email'],
		'Password'=>$_POST['Password']
	));
if ($check) {
		header("Location:../register.php?status=ok");
	}else{
		header("Location:../register.php?status=no");
			}
		}
		else{header("Location:../register.php?status=no");}
	}
