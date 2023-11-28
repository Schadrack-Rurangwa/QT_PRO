<?php
// Include your database connection code here
// $con = new mysqli("hostname", "username", "password", "database");

$sqlSelectUser = $con->prepare("SELECT id, FirstName, LastName FROM users ORDER BY FirstName ASC");
$sqlSelectUser->execute();
$resultSelectUser = $sqlSelectUser->get_result();

echo '<div class="forminput">';
echo '<label>Assignee</label>';
echo '<select name="Assignee[]" required="">';
echo '<option>Select Assignee</option>';

while ($RowSelectUser = mysqli_fetch_array($resultSelectUser)) {
    $Selectid = $RowSelectUser['id'];
    $SelectFirstName = $RowSelectUser['FirstName'];
    $SelectLastName = $RowSelectUser['LastName'];
    echo "<option value=\"$Selectid\">$SelectFirstName $SelectLastName</option>";
}

echo '</select>';
echo '<button type="button" onclick="removeAssignee(this)">Remove Assignee</button>';
echo '</div>';
?>
