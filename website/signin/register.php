<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

#TODO: need to check if we still need the Bootstrap header and footer. We might need to delete this stuff.
#
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

    <title>Register page!</title>
  </head>
  <h1>Register</h1>
  <hr>
  <?php 
  if(@$_GET['status']=="emailalredyexists"){
  ?>
  <div class="alert alert-danger">
      Email already exists
  </div>
  <?php } ?>
  <?php 
  if(@$_GET['status']=="weakpassword"){
  ?>
  <div class="alert alert-danger">
      Weak password
  </div>
  <?php } ?>
  <?php 
  if(@$_GET['status']=="no"){
  ?>
  <div class="alert alert-danger">
    Error! Username or password is wrong...
  </div>
  <?php } ?>
  <?php 
  if(@$_GET['status']=="exit"){
  ?>
  <div class="alert alert-danger">
   exit
  </div>
  <?php } ?>
  <?php 
  if(@$_GET['status']=="ok"){
  ?>
  <div class="alert alert-success">
    OK
  </div>
  <?php } ?>

  <body>
    <?php 
    if(isset($_SESSION['username'])){
    ?>
    <p>Welcome to your home page,<?php echo $_SESSION['username']; ?></p>
    <a href="logout.php"><button class="btn btn-success">exit</button></a>
    <?php } ?>

  <form class="container" action="../global/crud.php" method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">FirstName</label>
    <input type="text" class="form-control"  name="FirstName" placeholder="Enter email">  
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">LastName</label>
    <input type="text" class="form-control"  name="LastName" placeholder="Enter email">  
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">email</label>
    <input type="text" class="form-control"  name="Email" placeholder="Enter email">  
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Password</label>
    <input type="text" class="form-control"  name="Password" placeholder="Enter email">  
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Registration key</label>
    <input type="text" class="form-control"  name="Key" placeholder="Enter email">  
  </div>
  
  
  <button type="submit" name="register" class="btn btn-primary">Submit</button>
</form>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
  </body>
</html>
