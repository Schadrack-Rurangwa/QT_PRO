<?php
if (isset($_POST['submit'])) {
    $ProjectName = $_POST['ProjectName'];
    $StartDate = $_POST['StartDate'];
    $EndDate = $_POST['EndDate'];
    $project = $_POST['project'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $assignees = isset($_POST['Assignee']) ? $_POST['Assignee'] : []; // Assignee is an array

    // Insert data into the projects table
    $Status = "Original";
    $sqlInsertProject = $con->prepare("INSERT INTO projects (TaskName, StartDate, EndDate, Projects, Description, Priority, Status, CreateDate) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $sqlInsertProject->bind_param("sssssss", $ProjectName, $StartDate, $EndDate, $project, $description, $priority, $Status);
    $sqlInsertProject->execute();

    // Get the project ID that was just inserted
    $projectId = $sqlInsertProject->insert_id;

    // Insert assignees into the assignee_table
    foreach ($assignees as $assigneeId) {
        $sqlInsertAssignee = $con->prepare("INSERT INTO assignee_table (project_id, assignee_id) VALUES (?, ?)");
        $sqlInsertAssignee->bind_param("ss", $projectId, $assigneeId);
        $sqlInsertAssignee->execute();
    }

    // Check if both inserts were successful
    if ($sqlInsertProject && $sqlInsertAssignee) {
        echo "<script>alert('Record has been saved successfully!')</script>";
    } else {
        echo "Cannot insert" . mysqli_error($con);
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
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "')</script>";
    unset($_SESSION['message']);
}
?>

<div class="container">
    <form class="myform" action="project.php" method="post">

        <!-- Existing form fields -->

        <!-- Container for assignees with an id for easier manipulation -->
        <div id="assigneeContainer">
            <!-- Existing Assignee dropdown -->
            <div class="forminput">
                <label>Assignee</label>
                <select name="Assignee[]" required="">
                    <option>Select Assignee</option>
                    <?php
                    // Your existing code for fetching and displaying assignees
                    ?>
                </select>
                <!-- Add a Remove Assignee button -->
                <button type="button" onclick="removeAssignee(this)">Remove Assignee</button>
            </div>
        </div>

        <!-- Add a button to trigger the addition of new assignees -->
        <button type="button" onclick="addAssignee()">Add Assignee</button>

        <!-- Existing form buttons -->

    </form>
</div>

<script>
function addAssignee() {
    // Use AJAX to fetch and display the list of assignees
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Append the new assignee dropdown to the assigneeContainer
            document.getElementById("assigneeContainer").innerHTML += this.responseText;
        }
    };
    xhttp.open("GET", "get_assignees.php", true);
    xhttp.send();
}

function removeAssignee(button) {
    // Remove the parent div of the clicked button (which contains the assignee dropdown)
    button.parentNode.remove();
}
</script>
</body>
</html>













