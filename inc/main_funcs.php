<?php
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
        
            if ($interval->days < 1) {
                $timeRemaining = "today";
            } else {
                $timeRemaining = $interval->format('%a days');
            }
        
            $subscription = "Expires in " . $timeRemaining;
        }        

        echo '<center style="color: rgb(255, 255, 255, 0.7);">
                <center><h1 class="jebanelogo" style="font-size: 25px;">'.$_SESSION["username"].'</h1></center>
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
        if($_SESSION['admin'] > 0) {
            echo '<a href="admin.php" class="chujowyprzycisk" style="border: 1px solid rgb(92, 103, 255); color: rgb(92, 103, 255); padding: 5px 10px;"><i class="fa fa-lock" aria-hidden="true"></i> Admin</a>';
        }
    }

    function display_error()
    {
        $type = isset($_SESSION['error_style']) ? $_SESSION['error_style'] : 0;
        if (isset($_SESSION['error_message'])) {
            if($type == 0) {
                echo "<div class='jebanybox' style='border: 1px solid rgba(255, 0, 0); background: rgba(255, 0, 0, 0.1)'>" . $_SESSION['error_message'] . "</div><br>";
            } else {
                echo "<div class='jebanybox' style='border: 1px solid rgba(0, 255, 0); background: rgba(0, 255, 0, 0.1)'>" . $_SESSION['error_message'] . "</div><br>";
            }
            unset($_SESSION['error_message']);
        }
    }


    function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_-+=></?';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
?>