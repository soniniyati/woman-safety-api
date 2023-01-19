<?php
include "include/header.php";
include "include/navbar.php";
// if user is not logged in redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$db = getDBConnection();

// get user details from Get request
$id = $_GET['id'];
// get user details from database
$user = $db->from('user')
    ->where('id')->is($id)
    ->select()
    ->first();
?>

<!--Form to Edit user-->
<div class="flex justify-center	mt-8">
    <div class="m-5">
        <form class="grid grid-cols-3 gap-4 content-center" action="script/update_user.php" method="post">
            <div>
                <label for="first_name">First Name</label>
                <input value="<?=$user->first_name?>" type="text" placeholder="Type here" class="input input-bordered input-accent w-full max-w-xs" />
            </div>
            <div>
                <label for="first_name">Middle Name</label>
                <input value="<?=$user->middle_name?>" type="text" placeholder="Type here" class="input input-bordered input-accent w-full max-w-xs" />
            </div>
            <div>
                <label for="first_name">Last Name</label>
                <input value="<?=$user->last_name?>" type="text" placeholder="Type here" class="input input-bordered input-accent w-full max-w-xs" />
            </div>
            <div>
                <label for="first_name">Email</label>
                <input value="<?=$user->email?>" type="text" placeholder="Type here" class="input input-bordered input-accent w-full max-w-xs" />
            </div>
            <div>
                <label for="first_name">Gender</label>
                <select class="select select-bordered w-full max-w-xs">
                    <option disabled selected><?=$user->gender?></option>
                    <option>Male</option>
                    <option>Female</option>
                    <option>Other</option>
                </select>
            </div>
            <div>
                <label for="first_name">Dob</label>
                <input value="<?=$user->dob?>" type="datetime-local" placeholder="Type here" class="input input-bordered input-accent w-full max-w-xs" />
            </div>
           <div>
               <label for="first_name">District</label>
               <input value="<?=$user->district?>" type="text" placeholder="Type here" class="input input-bordered input-accent w-full max-w-xs" />
           </div>
            <div>
                <label for="first_name">Address</label>
                <input type="text"value="<?=$user->address?>"  placeholder="Type here" class="input input-bordered input-accent w-full max-w-xs" />
            </div>
            <div>
                <label for="first_name">Status</label>
                <select class="select select-bordered w-full max-w-xs">
                    <option disabled selected><?=$user->status?></option>
                    <option>Active</option>
                    <option>Block</option>
                </select>
            </div>
            <div class="btn-group">
                <button class="btn btn-success text-white">Update</button>
                <button type="reset" class="btn btn-warning">Reset</button>
                <a href="script/delete.php?id=<?=$user->id?>" class="btn btn-error text-white">Delete</a>
            </div>
        </form>
    </div>
</div>
