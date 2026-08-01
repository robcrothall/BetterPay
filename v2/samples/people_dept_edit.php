<?php
/**
 * Program: people_dept_edit
 * 
 * Edit staff department assignments.
 * 
 * PHP version 7.1
 * 
 * @category Template
 * @package  Sample
 * @author   "Rob Crothall" <rob@crothall.co.za>
 * @license  GPL 1.0 or later
 * @version  GIT: <git_id>
 * @link     http://www.sprv.co.za
 */
require "../inc/config.php"; 
$_SESSION["module"] = $_SERVER["PHP_SELF"];
require "../inc/head.php";
require "../inc/body.php";
require "../inc/menu.php";
require "../inc/msg.php";

function render_people_dept_edit_form($id, $people_id, $dept_id, $expires) {
    global $handle;
    include "../inc/db_open.php";
    ?>

<h1>Edit staff department assignment</h1>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<table cellspacing="5" cellpadding="5">
<tr>
    <td valign="top">Staff member</td>
    <td>
        <select name="people_id" required>
            <option value="">-- Select staff member --</option>
    <?php
    $sql = "select id, surname, first_name, given_name from people ";
    $sql .= "where id in (select people_id from memberships where group_id in (3, 4)) ";
    $sql .= "order by surname, first_name";
    foreach ($handle->query($sql) as $person) {
        $selected = ($person['id'] == $people_id) ? ' selected' : '';
        $name = trim($person['surname']);
        if (strlen(trim($person['first_name'])) > 0) {
            $name .= ", " . trim($person['first_name']);
        }
        if (isset($person['given_name']) && strlen(trim($person['given_name'])) > 0) {
            $name .= " (" . trim($person['given_name']) . ")";
        }
        echo '<option value="' . $person['id'] . '"' . $selected . '>' . $name . '</option>';
    }
    ?>
        </select>
    </td>
</tr>
<tr>
    <td valign="top">Department</td>
    <td>
        <select name="dept_id" required>
            <option value="">-- Select department --</option>
    <?php
    $sql = "select id, dept_name from dept order by dept_name";
    foreach ($handle->query($sql) as $dept) {
        $selected = ($dept['id'] == $dept_id) ? ' selected' : '';
        echo '<option value="' . $dept['id'] . '"' . $selected . '>' . trim($dept['dept_name']) . '</option>';
    }
    ?>
        </select>
    </td>
</tr>
<tr>
    <td valign="top">Expiry date</td>
    <td><input type="date" name="expires" value="<?php echo $expires; ?>" required></td>
</tr>
<tr>
    <td colspan=2><input type="submit" name="submit" value="Update" 
        class='w3-button w3-green'/>&nbsp;
        <a class="w3-button w3-green" href="../page/people_dept_list.php">
            Return to assignment list</a>
        &nbsp;
    </td>
</tr>
</table>
</form>
    <?php
}

if ($_SERVER["REQUEST_METHOD"] <> "POST") {
    include "../inc/db_open.php";
    $id = test_input($_GET["id"] ?? '');
    if (trim($id) == '') {
        die("No ID specified - please inform SysAdmin.");
    }
    $sql = "select pd.id, pd.people_id, pd.dept_id, pd.expires ";
    $sql .= "from people_dept pd where pd.id = " . intval($id);
    $rows = query($sql);
    if (count($rows) == 0) {
        die("No result returned from database - please advise SysAdmin.");
    }
    $row = $rows[0];
    $people_id = $row["people_id"];
    $dept_id = $row["dept_id"];
    $expires = $row["expires"];
    render_people_dept_edit_form($id, $people_id, $dept_id, $expires);
    include "../inc/footer.php";
    exit;
}

include "../inc/msg.php";
echo '<h1>Edit a staff department assignment</h1>';
$errorList = array();
$id = test_input($_POST["id"] ?? '');
$people_id = test_input($_POST["people_id"] ?? '');
$dept_id = test_input($_POST["dept_id"] ?? '');
$expires = test_input($_POST["expires"] ?? '');
$user_id = $_SESSION["id"];
if (!is_numeric($id) || intval($id) <= 0) {
    $errorList[] = "Invalid assignment ID.";
}
if (!is_numeric($people_id) || intval($people_id) <= 0) {
    $errorList[] = "Please select a staff member.";
}
if (!is_numeric($dept_id) || intval($dept_id) <= 0) {
    $errorList[] = "Please select a department.";
}
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $expires)) {
    $errorList[] = "Please enter a valid expiry date.";
}
include "../inc/db_open.php";
if (sizeof($errorList) == 0) {
    global $handle;
    include "../inc/db_open.php";
    $id = mysqli_real_escape_string($handle, $id);
    $people_id = mysqli_real_escape_string($handle, $people_id);
    $dept_id = mysqli_real_escape_string($handle, $dept_id);
    $expires = mysqli_real_escape_string($handle, $expires);
    $sql = "select count(*) as kount from people_dept ";
    $sql .= "where people_id = " . $people_id;
    $sql .= " and dept_id = " . $dept_id;
    $sql .= " and id <> " . $id;
    foreach ($handle->query($sql) as $row) {
        if ($row["kount"] > 0) {
            $errorList[] = "This staff member is already assigned to that department.";
        }
    }
}
if (sizeof($errorList) == 0) {
    $sql = "update people_dept set people_id = " . $people_id;
    $sql .= ", dept_id = " . $dept_id;
    $sql .= ", expires = '" . $expires . "'";
    $sql .= ", user_id = " . $user_id;
    $sql .= " where id = " . $id;
    $result = query($sql);
    echo "Update successful.<br><br>";
    echo '<a href="../page/people_dept_list.php" class="w3-button w3-green">Back to assignment list</a>';
    include "../inc/footer.php";
    exit;
}
echo '<div class="w3-panel w3-pale-red w3-border w3-border-red">';
echo '<strong>The following errors were encountered:</strong>';
echo '<ul>';
for ($x = 0; $x < sizeof($errorList); $x++) {
    echo "<li>$errorList[$x]";
}
echo '</ul>';
echo '</div>';
