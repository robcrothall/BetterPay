<?php
/**
 * Program: people_dept_add
 * 
 * Maintain staff department assignments.
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

function render_people_dept_add_form($people_id, $dept_id, $expires) {
    global $handle;
    include "../inc/db_open.php";
    ?>

<h1>Add a staff department assignment</h1>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
<table cellspacing="5" cellpadding="5">
<tr>
    <td valign="top">Staff member</td>
    <td>
        <select name="people_id" required>
            <option value="">-- Select staff member --</option>
    <?php
    $sql = "select id, surname, first_name, given_name from people ";
    $sql .= "where id in (select people_id from memberships where group_id in (3, 4, 37)) ";
    $sql .= "order by surname, first_name";
    foreach ($handle->query($sql) as $row) {
        $selected = ($row['id'] == $people_id) ? ' selected' : '';
        $name = trim($row['surname']);
        if (strlen(trim($row['first_name'])) > 0) {
            $name .= ", " . trim($row['first_name']);
        }
        if (isset($row['given_name']) && strlen(trim($row['given_name'])) > 0) {
            $name .= " (" . trim($row['given_name']) . ")";
        }
        echo '<option value="' . $row['id'] . '"' . $selected . '>' . $name . '</option>';
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
    foreach ($handle->query($sql) as $row) {
        $selected = ($row['id'] == $dept_id) ? ' selected' : '';
        echo '<option value="' . $row['id'] . '"' . $selected . '>' . trim($row['dept_name']) . '</option>';
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
    <td colspan=2>
        <input type="submit" name="submit" value="Add" 
            class="w3-button w3-green"/>&nbsp;
        <a class="w3-button w3-green" href="../page/people_dept_list.php">
            Return to assignment list</a>&nbsp;
    </td>
</tr>
</table>
</form>
    <?php
}

$people_id = '';
$dept_id = '';
$expires = '2050-12-31';
$errorList = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $people_id = test_input($_POST["people_id"] ?? '');
    $dept_id = test_input($_POST["dept_id"] ?? '');
    $expires = test_input($_POST["expires"] ?? '');
    $user_id = $_SESSION["id"];
    if (!is_numeric($people_id) || intval($people_id) <= 0) {
        $errorList[] = "Please select a staff member.";
    }
    if (!is_numeric($dept_id) || intval($dept_id) <= 0) {
        $errorList[] = "Please select a department.";
    }
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $expires)) {
        $errorList[] = "Please enter a valid expiry date.";
    }
    global $handle;
    include "../inc/db_open.php";
    if (sizeof($errorList) == 0) {
        $people_id = mysqli_real_escape_string($handle, $people_id);
        $dept_id = mysqli_real_escape_string($handle, $dept_id);
        $expires = mysqli_real_escape_string($handle, $expires);
        $sql = "select count(*) as kount from people_dept ";
        $sql .= "where people_id = " . $people_id;
        $sql .= " and dept_id = " . $dept_id;
        foreach ($handle->query($sql) as $row) {
            if ($row["kount"] > 0) {
                $errorList[] = "This staff member is already assigned to that department.";
            }
        }
    }
    if (sizeof($errorList) == 0) {
        $sql = "insert into people_dept (people_id, dept_id, expires, user_id) values (";
        $sql .= (int)$people_id . ", " . (int)$dept_id . ', "' . $expires . '", ' . (int)$user_id . ')';
        // echo '<br>SQL: ' . $sql . '</b><br>';
        $result = mysqli_query($handle, $sql)
            or die("Error in query: $sql. " . mysqli_error($handle));
        echo "Staff department assignment added successfully.<br><br>";
        echo '<a class="w3-button w3-green" href="../page/people_dept_list.php">Back to assignment list</a>';
        include "../inc/msg.php";
        include "../inc/footer.php";
        exit;
    } else {
        echo '<div class="w3-panel w3-pale-red w3-border w3-border-red">';
        echo '<strong>The following errors were encountered:</strong>';
        echo '<ul>';
        for ($x = 0; $x < sizeof($errorList); $x++) {
            echo "<li>$errorList[$x]";
        }
        echo '</ul>';
        echo '</div>';    
    }
}

render_people_dept_add_form($people_id, $dept_id, $expires);
include "../inc/footer.php";
?>
