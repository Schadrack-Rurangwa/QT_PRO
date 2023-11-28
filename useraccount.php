<?php
if(!isset($_SESSION)) 
{ 
session_start(); 
}
date_default_timezone_set("Africa/Kigali");
$Now= date('Y-m-d H:i:s');

require_once('connection.php');

$Selectid="";
$SelectFirstName="";
$SelectLastName="";
$SelectUserName="";
$SelectPriority="";
$SelectPhone="";
$SelectEmail="";


if(isset($_POST['save']))
{
$FirstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$username = $_POST['username'];
$password = mysqli_real_escape_string($con, $_POST['password']);
$password = password_hash($password , PASSWORD_DEFAULT);
$priority = $_POST['priority'];
$phone = $_POST['phone'];
$nationid = $_POST['nationid'];
$email = $_POST['email'];

$Status="Active";
$sqlInsertU = $con->prepare("insert into users (FirstName, LastName, UserName, Password, Priority, Active, Phone, Nationalid, email, CreateDate) value(?,?,?,?,?,?,?,?,?,?)");
$sqlInsertU->bind_param("ssssssssss", $FirstName,$lastName,$username,$password,$priority,$Status,$phone,$nationid,$email,$Now);
$sqlInsertU->execute();

if($sqlInsertU){
		echo"<script> ";
        echo"alert(\"Record has been saved successfully!\")";
        echo"</script>";
}
else{
	echo"Cannot insert".mysqli_error($con);
}
}


if(isset($_POST['update']))
{
$user = $_SESSION["user"];
$FirstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$username = $_POST['username'];
$password = mysqli_real_escape_string($con, $_POST['password']);
$password = password_hash($password , PASSWORD_DEFAULT);
$priority = $_POST['priority'];
$phone = $_POST['phone'];
$nationid = $_POST['nationid'];
$email = $_POST['email'];

$Status="Active";


$sqlUpdateU = $con->prepare("update users set FirstName= ?, LastName= ?, UserName= ?, Password= ?, Priority= ?, Phone= ?, Nationalid= ?, email= ?, UpdateDate= ? where id= ?");
$sqlUpdateU->bind_param("ssssssssss", $FirstName,$lastName,$username,$password,$priority,$phone,$nationid,$email,$Now,$_SESSION["user"]);
$sqlUpdateU->execute();

if($sqlUpdateU){		
	    $_SESSION['message'] = "Record has been updated successfully!";
		header('Location:profile.php');
		exit;		
}
else{
	echo"Cannot insert".mysqli_error($con);
}
}



if(isset($_POST['edit']))
{
$sqlSelectUser = $con->prepare("select users.id, users.FirstName, users.LastName, users.UserName, users.Password, users.Priority, users.Active, users.Phone, users.email from users where Id=? order by users.FirstName asc");
$sqlSelectUser->bind_param("s", $_SESSION["user"]);
$sqlSelectUser->execute();
$resultSelectUser= $sqlSelectUser->get_result();
$sqlNumUser=mysqli_num_rows($resultSelectUser);
$Selectid="";
$SelectFirstName="";

while($RowSelectUser = mysqli_fetch_array($resultSelectUser))
{
$Selectid= $RowSelectUser['id'];
$SelectFirstName= $RowSelectUser['FirstName'];
$SelectLastName= $RowSelectUser['LastName'];
$SelectUserName= $RowSelectUser['UserName'];

$SelectPriority= $RowSelectUser['Priority'];
$SelectPhone= $RowSelectUser['Phone'];
$SelectEmail= $RowSelectUser['email'];
}
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <title>User Form</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="style3.css">
  <style>
    .error {
      color: red;
    }
  </style>
</head>
<body>

<div class="container">
  <form class="myform" action="useraccount.php" method="post">
    <h2><?php if($Selectid){ echo"Update your profile"; } else { echo"Registration Form"; } ?></h2>
    <div class="forminput">
      <label>First Name</label>
      <input type="text" name="firstName" required="" placeholder="Enter the first name" value= <?php if($Selectid){ echo"$SelectFirstName"; } ?>>
    </div>
    <div class="forminput">
      <label>Last Name</label>
      <input type="text" name="lastName" required="" placeholder="Enter the last name" value= <?php if($Selectid){ echo"$SelectLastName"; } ?>>
    </div>

    <div class="forminput">
      <label>Username</label>
      <input type="text" name="username" required="" placeholder="Enter the username" value= <?php if($Selectid){ echo"$SelectUserName"; } ?>>
    </div>
    <div class="forminput">
      <label>Password</label>
      <input type="password" id="password" name="password" required="" placeholder="Enter the password">
    </div>

    <div class="forminput">
      <label>Confirm Password</label>
      <input type="password" id="confirmPassword" name="password2" placeholder="Enter the password">
      <span id="passwordError" class="error"></span>
    </div>

    <div class="forminput">
      <label>Account Type</label>
      <select name="priority" required="">
        <option>Administrator</option>
        <option>End User</option>
      </select>
    </div>

    <div class="forminput">
      <label>Telephone</label>
      <input type="tel" name="phone" pattern="[0][7][8/2/3][0-9]{7}" required="" placeholder="Phone number 078...,072...,073..." value= <?php if($Selectid){ echo"$SelectPhone"; } ?>>
    </div>

    <div class="forminput">
      <label>National ID</label>
      <input type="tel" name="nationid" required="" placeholder="Enter Nation ID" value= <?php if($Selectid){ echo"$SelectUserName"; } ?>>
    </div>

    <div class="forminput">
      <label>Email</label>
      <input type="email" name="email" placeholder="Enter the email" value= <?php if($Selectid){ echo"$SelectEmail"; } ?>>
    </div>

    <?php if($Selectid){ echo"<input class=\"button\" type=\"submit\" name=\"update\" value=\"Update\">"; } else { echo"<input class=\"button\" type=\"submit\" name=\"save\" value=\"Save\">"; } ?>
    <input class="button" type="reset" name="reset" value="Clear">

    <?php if($Selectid){ echo"<a class=\"button\" href=\"profile.php\">Back</a>"; } else { echo"<a class=\"button\" href=\"index.php\">Back</a>"; } ?>
  </form>

  <script>
    // Get references to the password and confirm password fields
    var passwordField = document.getElementById('password');
    var confirmPasswordField = document.getElementById('confirmPassword');
    var passwordError = document.getElementById('passwordError');

    // Add input event listeners to the password and confirm password fields
    passwordField.addEventListener('input', validatePassword);
    confirmPasswordField.addEventListener('input', validatePassword);

    function validatePassword() {
      // Compare the values of the password and confirm password fields
      if (passwordField.value !== confirmPasswordField.value) {
        // If they don't match, display an error message
        passwordError.textContent = 'Passwords do not match';
      } else {
        // If they match, clear the error message
        passwordError.textContent = '';
      }
    }
  </script>
</div>

</body>
</html>
