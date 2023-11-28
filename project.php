<?php
if(!isset($_SESSION)) 
{ 
session_start(); 
}
date_default_timezone_set("Africa/Kigali");
$Now= date('Y-m-d H:i:s');

require_once('connection.php');

if(isset($_POST['submit']))
{

$ProjectName = $_POST['ProjectName'];
$StartDate = $_POST['StartDate'];
$EndDate = $_POST['EndDate'];
$Assignee = $_POST['Assignee'];
$project = $_POST['project'];
$description = $_POST['description'];
$priority = $_POST['priority'];
//$attach = $_POST['attach'];


/*
    // Handle file upload
    if ($_FILES["attach"]["error"] == UPLOAD_ERR_OK) {
        $uploadDir = 'Attachment/';
        $uploadFile = $uploadDir . basename($_FILES["attach"]["name"]);

        // Check if the file type is allowed (PDF, Word, Excel)
        $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
        $fileExtension = pathinfo($_FILES["attach"]["name"], PATHINFO_EXTENSION);

        if (in_array(strtolower($fileExtension), $allowedExtensions)) {
            // Move the uploaded file to the specified directory
            if (move_uploaded_file($_FILES["attach"]["tmp_name"], $uploadFile)) {
                echo "File is valid, and was successfully uploaded.";
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "Invalid file type. Allowed file types are PDF, Word, and Excel.";
        }
    } else {
        echo "Error during file upload.";
    }
*/



$Status="Original";
$sqlInsertU = $con->prepare("insert into projects (TaskName, StartDate, EndDate, Assignee, Projects, Description, Priority, Status, CreateDate) value(?,?,?,?,?,?,?,?)");
$sqlInsertU->bind_param("ssssssss", $ProjectName,$StartDate,$EndDate,$Assignee,$project,$description,$priority,$Status,$Now);
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


if(isset($_POST['draft']))
{
$ProjectName = $_POST['ProjectName'];
$StartDate = $_POST['StartDate'];
$EndDate = $_POST['EndDate'];
$Assignee = $_POST['Assignee'];
$project = $_POST['project'];
$description = $_POST['description'];
$priority = $_POST['priority'];
//$attach = $_POST['attach'];

$Status="Draft";
$sqlInsertU = $con->prepare("insert into projects (TaskName, StartDate, EndDate, Assignee, Projects, Description, Priority, Status, CreateDate) value(?,?,?,?,?,?,?,?)");
$sqlInsertU->bind_param("ssssssss", $ProjectName,$StartDate,$EndDate,$Assignee,$project,$description,$priority,$Status,$Now);
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
?>



<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="styleproject1.css">
<title></title>
</head>
<body>

<?php
if(!isset($_SESSION)) 
{ 
  session_start(); 
}
 
if(isset($_SESSION['message'])){
 echo "<script>alert('".$_SESSION['message']."')</script>";
 unset($_SESSION['message']);
}
 ?>

 
 

<div class="container">
<form class="myform" action="project.php" method="post">

<table>
<tr>
<td><a class="button" href="profile.php">My Profile</a></td>
</tr>
</table>
<h3>Create Task</h3>
	

<div class="forminput">
	<label>Name</label>
	<input type="text" name="ProjectName" required="" placeholder="" value="">
</div>

<div class="forminput">
	<label>Start Date</label>
	<input type="date" name="StartDate" required="" placeholder="" value="">
</div>

<div class="forminput">
	<label>End Date</label>
	<input type="date" name="EndDate" required="" placeholder="" value="">
</div>

<div class="forminput">
	<label>Assignee</label>    
<select name="Assignee" required="">
<option>Select Assignee</option>
<?php
$sqlSelectUser = $con->prepare("select users.id, users.FirstName, users.LastName, users.LastName, users.UserName, users.Password, users.Priority, users.Active, users.Phone, users.email from users order by users.FirstName asc");
$sqlSelectUser->execute();
$resultSelectUser= $sqlSelectUser->get_result();
$sqlNumUser=mysqli_num_rows($resultSelectUser);
while($RowSelectUser = mysqli_fetch_array($resultSelectUser))
{
$Selectid= $RowSelectUser['id'];
$SelectFirstName= $RowSelectUser['FirstName'];
$SelectLastName= $RowSelectUser['LastName'];
echo"<option value=\"$Selectid\">$SelectFirstName $SelectLastName</option>";
}
?>
</select>
	
</div>

    <div class="forminput">
      <label>Projects</label>
      <textarea name="project" id="project" style="height: 50px;" placeholder="Project Name"></textarea>
      <span id="projectCharCountError" class="error"></span>
    </div>

    <div class="forminput">
      <label>Description</label>
      <textarea name="description" id="description" style="height: 80px;" placeholder="Add more details to this task"></textarea>
      <span id="descriptionCharCountError" class="error"></span>
    </div>

<div class="forminput">
	<label>Priority</label>
	<input type="radio" name="priority" required="" value="Low"> Low
	<input type="radio" name="priority" required="" value="Normal"> Normal
	<input type="radio" name="priority" required="" value="High"> High
</div>


<div class="forminput">
  <label>Attachment</label>
  <input type="file" name="attach" id="attachment" accept=".pdf, .doc, .docx, .xls, .xlsx" required="">
</div>


<a class="button" href="index.php">Cancel</a>
<input type="reset" class="button" name="clear" value="Reset"/>
<input type="submit" class="button" name="Draft" value="Save Draft"/>	
<input type="submit" class="button" name="submit" value="Submit"/>	
</form>
</div>
</body>
</html>



    <script>
      var projectTextarea = document.getElementById('project');
      var descriptionTextarea = document.getElementById('description');
      var projectCharCountError = document.getElementById('projectCharCountError');
      var descriptionCharCountError = document.getElementById('descriptionCharCountError');

      projectTextarea.addEventListener('input', function() {
        var charCount = projectTextarea.value.length;
        if (charCount > 60) {
          // If character count exceeds 60, prevent further input and display error message
          projectTextarea.value = projectTextarea.value.slice(0, 60);
          projectCharCountError.textContent = 'Characters exceed 60';
          projectTextarea.style.borderColor = 'red';
        } else {
          // If within the limit, clear the error message and border color
          projectCharCountError.textContent = '';
          projectTextarea.style.borderColor = '';
        }
      });

      descriptionTextarea.addEventListener('input', function() {
        var charCount = descriptionTextarea.value.length;
        if (charCount > 60) {
          // If character count exceeds 60, prevent further input and display error message
          descriptionTextarea.value = descriptionTextarea.value.slice(0, 60);
          descriptionCharCountError.textContent = 'Characters exceed 60';
          descriptionTextarea.style.borderColor = 'red';
        } else {
          // If within the limit, clear the error message and border color
          descriptionCharCountError.textContent = '';
          descriptionTextarea.style.borderColor = '';
        }
      });
    </script>














    <script>
      /*var descriptionTextarea = document.getElementById('description');
      var charCountError = document.getElementById('charCountError');

      descriptionTextarea.addEventListener('input', function() {
        var charCount = descriptionTextarea.value.length;
        if (charCount > 60) {
          // If character count exceeds 60, prevent further input and display error message
          descriptionTextarea.value = descriptionTextarea.value.slice(0, 60);
          charCountError.textContent = 'Characters exceed 60';
          descriptionTextarea.style.borderColor = 'red';
        } else {
          // If within the limit, clear the error message and border color
          charCountError.textContent = '';
          descriptionTextarea.style.borderColor = '';
        }
      });*/
    </script>