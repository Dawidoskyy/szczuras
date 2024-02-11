<?php
    session_start();

    function show_login()
    {
        echo '
        <form action="./auth.php" method="post">
            <label for="login_key" style="float: left;">Enter key: <span style="color: red">*</span></label><br>
            <input type="text" id="login_key" name="login_key" style="width: 90%;"><br><br>
            <center><input type="submit" value="Submit" class="chujowyprzycisk"></center>
        </form>';
    }

    function show_price_list()
    {
        echo '
            <center><h1 style="color: rgb(255, 255, 255, 0.7);">Price list:</h1></center>
            <ul>
                <li>
                    30 days: 30pln
                </li>
                <li>
                    Lifetime: 50pln
                </li>
                <li>
                    Delete from database: 20pln
                </li>
            </ul>
        ';
    }

    function show_user_info()
    {
        $subscription = "None";
        $now = new DateTime(); // Current date and time
        if(isset($_SESSION['subscription']) && $_SESSION['subscription'] > $now->getTimestamp()) {
            $subscriptionEndDate = new DateTime();
            $subscriptionEndDate->setTimestamp($_SESSION['subscription']); 
            $interval = $now->diff($subscriptionEndDate); 
            
            $timeRemaining = $interval->format('%a days');
            $subscription = "Expires in " . $timeRemaining;
        }

        echo '<center style="color: rgb(255, 255, 255, 0.7);">
                <center><h1>'.$_SESSION["username"].'</h1></center>
                <p>Subscription: '.$subscription.'</p>
                <p>Lookups: '.$_SESSION['lookups'].'</p>
            </center>
        ';
    }

    function user_panel()
    {
        echo '
            <form action="./search.php" method="post">
                <label for="search_user" style="float: left;">Search user: <span style="color: red">*</span></label><br>
                <input type="text" id="search_user" name="search_user" style="width: 90%;"><br><br>
                <center>
                    <button type="submit" class="chujowyprzycisk"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                </center>
            </form>  

            <br><a href="logout.php" class="chujowyprzycisk" style="border: 1px solid rgb(255, 0, 0); color: red; padding: 5px 10px;"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
            ';
    }

    function display_error()
    {
        $type = $_SESSION['error_style'];
        if (isset($_SESSION['error_message'])) {
            if($type == 0) {
                echo "<div class='jebanybox' style='border: 1px solid rgba(255, 0, 0); background: rgba(255, 0, 0, 0.1)'>" . $_SESSION['error_message'] . "</div><br>";
            } else {
                echo "<div class='jebanybox' style='border: 1px solid rgba(0, 255, 0); background: rgba(0, 255, 0, 0.1)'>" . $_SESSION['error_message'] . "</div><br>";
            }
            unset($_SESSION['error_message']);
        }
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
    </head>
    <body>
        <h1 class="jebanelogo">szczuras.fun</h1><br><br><br>

        <?php display_error(); ?>

        <div class="jebanybox">
           <?php
                if (isset($_SESSION['authkey'])) {
                    user_panel();
                } else {
                    show_login();
                }
           ?>
            
        </div>
        <br>
        <!-- Cennik-->
        <div class="jebanybox">
            <?php
                if (isset($_SESSION['authkey'])) {
                    show_user_info();
                } else {
                    show_price_list();
                }
            ?>
        </div>

        <a href="https://discord.gg/K7f8Yehy8n" class="media"><i class="fa-brands fa-discord"></i> Discord</a>

        <!-- Pokazywanie okna modalnego -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <?php echo $_SESSION['modal_message'] ?>
            </div>
        </div>

        <!-- Pokazywanie wiadomosci do okna -->
        <?php if(isset($_SESSION['modal_message'])) { ?>
            <script>var modal = document.getElementById("myModal");
            modal.style.display = "block";</script>
        <?php unset($_SESSION['modal_message']); } ?>
    </body>
</html>

<script>
    var modal = document.getElementById("myModal");
    var btn = document.getElementsByTagName("button")[0];
    var closeBtn = document.getElementsByClassName("close")[0];

    function closeModal() {
        modal.style.display = "none";
    }
    closeBtn.onclick = closeModal;

    function openModal() {
        modal.style.display = "block";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>