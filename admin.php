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
    <title>szczuras.fun</title>
    <style>
        .hidden { display: none; }
    </style>
</head>
<body>
    <h1 class="jebanelogo">Admin panel</h1><br><br><br>

    <?php display_error(); ?>

    <!-- Action Selection -->
    <div class="jebanybox">
        <select id="actionSelector" onchange="showForm()">
            <option value="">Select Action...</option>
            <option value="createKey">Generate Key</option>
            <option value="addDays">Add Days to Key</option>
            <option value="addBlacklist">Blacklist username</option>
            <option value="removeBlacklist">Remove blacklist</option>
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

    <a href="index.php" class="media"><i class="fa fa-arrow-left" aria-hidden="true"></i> Return back</a>

    <script>
        function showForm() {
            var action = document.getElementById("actionSelector").value;
            var createKeyForm = document.getElementById("createKeyForm");
            var addDaysForm = document.getElementById("addDaysForm");
            var blacklistUsername = document.getElementById("blacklistUsername");
            var blacklistRemove = document.getElementById("blacklistRemove");

            // Hide both forms initially
            createKeyForm.classList.add("hidden");
            addDaysForm.classList.add("hidden");
            blacklistUsername.classList.add("hidden");
            blacklistRemove.classList.add("hidden");

            // Show the selected form
            if (action === "createKey") {
                createKeyForm.classList.remove("hidden");
            } else if (action === "addDays") {
                addDaysForm.classList.remove("hidden");
            } else if (action === "addBlacklist") {
                blacklistUsername.classList.remove("hidden");
            } else if (action === "removeBlacklist") {
                blacklistRemove.classList.remove("hidden");
            }
        }
    </script>
</body>
</html>
