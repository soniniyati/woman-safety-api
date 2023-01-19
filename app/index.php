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
            <th>#Regisration Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th>DOB</th>
            <th>Address</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <!-- row 1 -->
        <?php
        $sql = "SELECT * FROM `users`";

        // checking if the user exists
        $user = $db->from('user')->select()->all();
        // display the user data
        foreach ($user as $row) {
            echo "<tr>";
            echo "<td>" . $row->id . "</td>";
            echo "<td>" . $row->first_name, $row->middle_name, $row->last_name . "</td>";
            echo "<td>" . $row->email . "</td>";
            echo "<td>" . $row->gender . "</td>";
            echo "<td>" . $row->dob . "</td>";
            echo "<td>" . $row->address, $row->district . "</td>";
            echo "<td>" . $row->status . "</td>";
            echo "<td><a class='link-hover link-success' href='edit.php?id=" . $row->id . "'>Edit</a> | <a class='link-error link-hover' href='script/delete.php?id=" . $row->id . "'>Delete</a></td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>

<!--    Display session error messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <div class="flex-1">
                <label><?php echo $_SESSION['error']; ?></label>
                <button class="btn btn-clear text-white" aria-label="close" onclick="this.parentElement.remove();"></button>
            </div>
        </div>
    <?php endif; ?>
    <?php unset($_SESSION['error']); ?>
</div>
