<?php 
    session_start();
    require "inc/main_funcs.php";

    if($_SESSION['admin'] <= 0) {
        header('Location: logout.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">
    <link rel="stylesheet" href="styles/style.css">
    <title>gicik.xyz</title>
    <style>
        .hidden { display: none; }
    </style>
    <link rel="icon" href="https://cdn.discordapp.com/attachments/1084181053687746620/1207078195694931979/LnBuZw.png?ex=65de562c&is=65cbe12c&hm=35597e7e27833b4e3c4db3bbab8493d411785354aafa424ef8e4a5adaf129ab4&" type="image/png">
</head>
<body>
    <h1 class="jebanelogo" onclick="window.location.href='index.php';" style="cursor: pointer;">Admin panel</h1><br><br><br>

    <?php display_error(); ?>

    <!-- Action Selection -->
    <div class="jebanybox">
        <select id="actionSelector" onchange="showForm()">
            <option value="">Select Action...</option>
            <option value="createKey">Generate Key</option>
            <option value="addDays">Add Days to Key</option>
            <option value="addBlacklist">Blacklist username</option>
            <option value="removeBlacklist">Remove blacklist</option>
            <option value="addNewLeak">Add new leak</option>
            <option value="addNewMassLeak">Add new massive leak</option>
            <option value="banUsers">Ban user</option>
            <option value="unbanUsers">Unban user</option>
        </select>
    </div><br>

    <!-- Generate Key Form -->
    <div id="createKeyForm" class="jebanybox hidden">
        <center><h3 style="color: rgb(255, 255, 255, 0.7);">Generate key:</h3></center>
        <form action="./admin_api.php" method="post">
            <input type="hidden" name="action" value="create_key">
            <label for="generate_username" style="float: left;">Username: <span style="color: red">*</span></label><br>
            <input type="text" id="generate_username" name="generate_username" style="width: 90%;"><br><br>
            <label for="generate_days" style="float: left;">Days: <span style="color: red">*</span></label><br>
            <input type="text" id="generate_days" name="generate_days" style="width: 90%;"><br><br>
            <center>
                <button type="submit" class="chujowyprzycisk"><i class="fa fa-plus" aria-hidden="true"></i> Execute</button>
            </center>
        </form>
    </div>

    <!-- Add Days to Key Form -->
    <div id="addDaysForm" class="jebanybox hidden">
        <center><h3 style="color: rgb(255, 255, 255, 0.7);">Add days for key:</h3></center>
        <form action="./admin_api.php" method="post">
            <input type="hidden" name="action" value="add_days">
            <label for="add_key" style="float: left;">Key: <span style="color: red">*</span></label><br>
            <input type="text" id="add_key" name="add_key" style="width: 90%;"><br><br>
            <label for="add_days" style="float: left;">Days: <span style="color: red">*</span></label><br>
            <input type="text" id="add_days" name="add_days" style="width: 90%;"><br><br>
            <center>
                <button type="submit" class="chujowyprzycisk"><i class="fa fa-plus" aria-hidden="true"></i> Execute</button>
            </center>
        </form>
    </div>

    <!-- Blacklist username -->
    <div id="blacklistUsername" class="jebanybox hidden">
        <center><h3 style="color: rgb(255, 255, 255, 0.7);">Blacklist username:</h3></center>
        <form action="./admin_api.php" method="post">
            <input type="hidden" name="action" value="blacklist_username">
            <label for="username" style="float: left;">Username: <span style="color: red">*</span></label><br>
            <input type="text" id="username" name="username" style="width: 90%;"><br><br>
            <center>
                <button type="submit" class="chujowyprzycisk"><i class="fa fa-plus" aria-hidden="true"></i> Execute</button>
            </center>
        </form>
    </div>

    <!-- Remove Blacklist -->
    <div id="blacklistRemove" class="jebanybox hidden">
        <center><h3 style="color: rgb(255, 255, 255, 0.7);">Remove blacklist:</h3></center>
        <form action="./admin_api.php" method="post">
            <input type="hidden" name="action" value="remove_blacklist">
            <label for="username" style="float: left;">Username: <span style="color: red">*</span></label><br>
            <input type="text" id="username" name="username" style="width: 90%;"><br><br>
            <center>
                <button type="submit" class="chujowyprzycisk"><i class="fa fa-plus" aria-hidden="true"></i> Execute</button>
            </center>
        </form>
    </div>

    <!-- Add leak -->
    <div id="userAdd" class="jebanybox hidden">
        <center><h3 style="color: rgb(255, 255, 255, 0.7);">Add new leak:</h3></center>
        <form action="./admin_api.php" method="post">
            <input type="hidden" name="action" value="add_new_leak">
            <label for="username" style="float: left;">Username: <span style="color: red">*</span></label><br>
            <input type="text" id="username" name="username" style="width: 90%;"><br><br>
            <label for="user_ip" style="float: left;">IP: <span style="color: red">*</span></label><br>
            <input type="text" id="user_ip" name="user_ip" style="width: 90%;"><br><br>
            <label for="leak_from" style="float: left;">From: <span style="color: red">*</span></label><br>
            <input type="text" id="leak_from" name="leak_from" style="width: 90%;"><br><br>
            <center>
                <button type="submit" class="chujowyprzycisk"><i class="fa fa-plus" aria-hidden="true"></i> Execute</button>
            </center>
        </form>
    </div>

    <!-- Add Mass Leaks -->
    <div id="massLeakAdd" class="jebanybox hidden">
        <center><h3 style="color: rgb(255, 255, 255, 0.7);">Add Mass Leaks:</h3></center>
        <form action="./admin_api.php" method="post">
            <input type="hidden" name="action" value="add_mass_leaks">
            <label for="mass_leaks" style="float: left;">Username / IP: <span style="color: red">*</span></label><br>
            <textarea id="mass_leaks" name="mass_leaks" style="width: 90%;" rows="5"></textarea><br><br>
            <label for="leak_from" style="float: left;">From: <span style="color: red">*</span></label><br>
            <input type="text" id="leak_from" name="leak_from" style="width: 90%;"><br><br>
            <center>
                <button type="submit" class="chujowyprzycisk"><i class="fa fa-plus" aria-hidden="true"></i> Execute</button>
            </center>
        </form>
    </div>

    <!-- Ban User Form -->
    <div id="banUser" class="jebanybox hidden">
        <center><h3 style="color: rgb(255, 255, 255, 0.7);">Ban User:</h3></center>
        <form action="./admin_api.php" method="post">
            <input type="hidden" name="action" value="ban_user">
            <label for="username" style="float: left;">Username: <span style="color: red">*</span></label><br>
            <input type="text" id="username" name="username" style="width: 90%;"><br><br>
            <label for="reason" style="float: left;">Reason: <span style="color: red">*</span></label><br>
            <input type="text" id="reason" name="reason" style="width: 90%;"><br><br>
            <label for="ban_days" style="float: left;">Ban Length: <span style="color: red">*</span></label><br>
            <input type="text" id="ban_days" name="ban_days" style="width: 90%;" placeholder=""><br><br>
            <center>
                <button type="submit" class="chujowyprzycisk"><i class="fa fa-plus" aria-hidden="true"></i> Ban User</button>
            </center>
        </form>
    </div>
    <div id="unbanUser" class="jebanybox hidden">
        <center><h3 style="color: rgb(255, 255, 255, 0.7);">Ban User:</h3></center>
        <form action="./admin_api.php" method="post">
            <input type="hidden" name="action" value="unban_user">
            <label for="username" style="float: left;">Username: <span style="color: red">*</span></label><br>
            <input type="text" id="username" name="username" style="width: 90%;"><br><br>
            <center>
                <button type="submit" class="chujowyprzycisk"><i class="fa fa-plus" aria-hidden="true"></i> Unban User</button>
            </center>
        </form>
    </div>


    <a href="index.php" class="media"><i class="fa fa-arrow-left" aria-hidden="true"></i> Return back</a>

    <script>
        function showForm() {
            var action = document.getElementById("actionSelector").value;
            var createKeyForm = document.getElementById("createKeyForm");
            var addDaysForm = document.getElementById("addDaysForm");
            var blacklistUsername = document.getElementById("blacklistUsername");
            var blacklistRemove = document.getElementById("blacklistRemove");
            var userAdd = document.getElementById("userAdd");
            var massLeakAdd = document.getElementById("massLeakAdd");
            var banUser = document.getElementById("banUser");
            var unbanUser = document.getElementById("unbanUser");

            // Hide both forms initially
            createKeyForm.classList.add("hidden");
            addDaysForm.classList.add("hidden");
            blacklistUsername.classList.add("hidden");
            blacklistRemove.classList.add("hidden");
            userAdd.classList.add("hidden");
            massLeakAdd.classList.add("hidden");
            banUser.classList.add("hidden");
            unbanUser.classList.add("hidden");

            // Show the selected form
            if (action === "createKey") {
                createKeyForm.classList.remove("hidden");
            } else if (action === "addDays") {
                addDaysForm.classList.remove("hidden");
            } else if (action === "addBlacklist") {
                blacklistUsername.classList.remove("hidden");
            } else if (action === "removeBlacklist") {
                blacklistRemove.classList.remove("hidden");
            } else if (action === "addNewLeak") {
                userAdd.classList.remove("hidden");
            } else if (action === "addNewMassLeak") {
                massLeakAdd.classList.remove("hidden");
            } else if (action === "banUsers") {
                banUser.classList.remove("hidden");
            } else if (action === "unbanUsers") {
                unbanUser.classList.remove("hidden");
            }
        }
    </script>
</body>
</html>
