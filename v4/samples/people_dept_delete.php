<?php
/**
 * Program: people_dept_delete
 * 
 * Delete a staff department assignment.
 * php version 7.2.10
 * 
 * @category WebPage
 * @package  Sample
 * @author   "Rob Crothall" <rob@crothall.co.za>
 * @license  GPL 1.0 or later
 * @version  GIT: <git_id>
 * @link     http://www.sprv.co.za
 */
static $handle;
require "../inc/config.php"; 
$_SESSION["module"] = $_SERVER["PHP_SELF"];
require "../inc/head.php";
require "../inc/body.php";
require "../inc/menu.php";
require "../inc/msg.php";
echo '<h1>Delete a staff department assignment</h1>';
$message = '';
if ((!isset($_GET['id'])) || (trim($_GET['id']) == '')) {
    $message = 'Missing record ID - please inform SysAdmin.';
} else {
    $req_id = test_input($_GET['id']);
    static $handle;
    include "../inc/db_open.php";
    $sql = "delete from people_dept where id = " . intval($req_id);
    $result = mysqli_query($handle, $sql);
    if ($result !== false) {
        $message = "Assignment deleted.";
    } else {
        $message = "Unable to delete assignment - please inform SysAdmin.";
    }
}
echo '<p>' . $message . '</p>';
echo '<br><br><a class="w3-button w3-green" ';
echo 'href="../page/people_dept_list.php">Back to assignment list</a>';
require "../inc/footer.php";
?>