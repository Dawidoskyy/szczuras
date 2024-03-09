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
        <title>gicik.xyz</title>
        <link rel="icon" href="https://cdn.discordapp.com/attachments/1084181053687746620/1207078195694931979/LnBuZw.png?ex=65de562c&is=65cbe12c&hm=35597e7e27833b4e3c4db3bbab8493d411785354aafa424ef8e4a5adaf129ab4&" type="image/png">
    </head>
    <body oncontextmenu="return false;">
        <h1 class="jebanelogo" onclick="window.location.href='index.php';" style="cursor: pointer;">gicik.xyz</h1><br><br><br>

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

        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <?php echo $_SESSION['modal_message'] ?>
            </div>
        </div>

        <?php if(isset($_SESSION['modal_message'])) { ?>
            <script>
                var modal = document.getElementById("myModal");
                modal.style.display = "block";
            </script>
        <?php unset($_SESSION['modal_message']); } ?>

        <script>
            window.addEventListener('devtoolschange', event => {
            document.body.innerHTML = "<center><h3>ogar mordko</h3></center>";
            });

            var modal = document.getElementById("myModal");
            var closeBtn = document.querySelector(".modal-content .close");

            function closeModal() {
                var opacity = 1;
                var timer = setInterval(function() {
                    if(opacity <= 0) {
                        clearInterval(timer);
                        modal.style.display = "none";
                    }
                    modal.style.opacity = opacity;
                    modal.style.filter = 'alpha(opacity=' + opacity * 100 + ")";
                    opacity -= 0.1;
                }, 15);
            }

            closeBtn.onclick = closeModal;

            window.onclick = function(event) {
                if (event.target == modal) {
                    closeModal();
                }
            }
            
        </script>

        <br>
        <?php if($_SESSION['admin'] >= 1) { ?>
            <!-- Chat -->
            <div class="jebanybox">
                <div id="chat">
                    
                </div>

                <form id="messageForm" action="chat/sendMessage.php" method="post">
                    <input type="text" name="message" id="message" placeholder="Message" style='width: 280px;' required>
                    <button type="submit" class="chujowyprzycisk" style="padding: 8px 12px;"><i class="fa fa-paper-plane" aria-hidden="true"></i> Send</button>
                </form>
            </div>
        <?php } ?>
    </body>
</html>

<script>
        function fetchMessages(){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("chat").innerHTML = this.responseText;
                    scrollChatToBottom();
                }
            };
            xhttp.open("GET", "getMessages.php", true);
            xhttp.send();
        }

        function scrollChatToBottom(){
            var chatDiv = document.getElementById('chat');
            chatDiv.scrollTop = chatDiv.scrollHeight;
        }

        document.addEventListener("DOMContentLoaded", function(event) { 
            fetchMessages();
            setInterval(fetchMessages, 5000);
        });

        document.getElementById("messageForm").addEventListener("submit", function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("message").value = "";
                    fetchMessages();
                }
            };
            xhttp.open("POST", "sendMessage.php", true);
            xhttp.send(formData);
        });
    </script>