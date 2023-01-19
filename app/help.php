<?php
include "include/header.php";
include "include/navbar.php";
// if user is not logged in redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$db = getDBConnection();
?>
<div class="overflow-x-auto m-5">
    <table class="table table-zebra w-full">
        <!-- head -->
        <thead>
        <tr>
            <th>#User Id</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Gender</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <!-- row 1 -->
        <?php
        $sql = "SELECT * FROM `emergency`";

        // checking if the user exists
        $user = $db->from('emergency')->select()->all();
        // display the user data
        foreach ($user as $row) {

        }
        ?>
        </tbody>
    </table>
</div>
