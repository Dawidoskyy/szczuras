<?php
    session_start();

    require "inc/main_funcs.php";
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">
        <link rel="stylesheet" href="styles/style.css">
        <title>Gicik.xyz</title>
    </head>
    <body>
        <h1 class="jebanelogo" onclick="window.location.href='index.php';" style="cursor: pointer;">Gicik.xyz</h1><br><br><br>

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