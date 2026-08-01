<?php
/**
 * Program: people_dept_list
 * 
 * List people department assignments.
 * php version 7.2.10
 * 
 * @category WebPage
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
require "../inc/db_open.php";
echo '<h1>Staff Department Assignments</h1>';
if (check_role("STAFF")) {
    echo '<a href="people_dept_add.php" class="w3-button w3-green">';
    echo 'Add a staff department assignment</a>';
}
$status_filter = 'active';
if (isset($_REQUEST['status_filter'])) {
    $status_filter = test_input($_REQUEST['status_filter']);
}
$all_selected = ($status_filter === 'all') ? ' selected' : '';
$active_selected = ($status_filter === 'active') ? ' selected' : '';
$expired_selected = ($status_filter === 'expired') ? ' selected' : '';
echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="GET" style="margin-top:12px; margin-bottom:12px;">';
    echo '<label for="status_filter">Show: </label>';
    echo '<select id="status_filter" name="status_filter">';
    echo '<option value="all"' . $all_selected . '>All</option>';
    echo '<option value="active"' . $active_selected . '>Active</option>';
    echo '<option value="expired"' . $expired_selected . '>Expired</option>';
    echo '</select>';
    echo ' <input type="submit" class="w3-button w3-green" value="Filter">';
echo '</form>';
?>
<table class="w3-table-all">
  <thead>
    <tr>
      <th>Staff Member</th>
      <th>Department</th>
      <th>Status</th>
      <th>Expires</th>
      <th>Last Changed</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
<?php
$sql = "select pd.id, pd.expires, pd.changed, ";
$sql .= "p.surname, p.first_name, p.given_name, d.dept_name ";
$sql .= "from people_dept pd ";
$sql .= "join people p on pd.people_id = p.id ";
$sql .= "join dept d on pd.dept_id = d.id ";
if ($status_filter === 'active') {
    $sql .= "where pd.expires >= CURDATE() ";
} elseif ($status_filter === 'expired') {
    $sql .= "where pd.expires < CURDATE() ";
}
$sql .= "order by p.surname, p.first_name, d.dept_name";
foreach ($handle->query($sql) as $row) {
    $full_name = trim($row['surname']);
    if (strlen(trim($row['first_name'])) > 0) {
        $full_name .= ", " . trim($row['first_name']);
    }
    if (isset($row['given_name']) && strlen(trim($row['given_name'])) > 0) {
        $full_name .= " (" . trim($row['given_name']) . ")";
    }
    $status = (trim($row['expires']) < date('Y-m-d')) ? 'Expired' : 'Active';
    echo '<tr>';
    echo '  <td>' . $full_name . '</td>';
    echo '  <td>' . trim($row['dept_name']) . '</td>';
    echo '  <td>' . $status . '</td>';
    echo '  <td>' . trim($row['expires']) . '</td>';
    echo '  <td>' . trim($row['changed']) . '</td>';
    echo '  <td>';
    if (check_role("STAFF")) {
        echo '<a class="w3-button w3-green" href="../page/people_dept_edit.php?id=';
        echo $row['id'] . '">Update</a>';
        echo '<a class="w3-button w3-red" href="../page/people_dept_delete.php?id=';
        echo $row['id'] . '">Delete</a>';
    }
    echo '  </td>';
    echo '</tr>';
}
?>
  </tbody>
</table>
<?php
require "../inc/footer.php";
?>