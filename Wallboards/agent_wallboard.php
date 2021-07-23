<?php
    /**
     * ------------------------------------------------------------------------
     *                               ADL CRM
     * ------------------------------------------------------------------------
     *
     * Copyright © 2018 ADL CRM All rights reserved.
     *
     * Unauthorised copying of this file, via any medium is strictly prohibited.
     * Unauthorised distribution of this file, via any medium is strictly prohibited.
     * Unauthorised modification of this code is strictly prohibited.
     *
     * Proprietary and confidential
     *
     * Written by Michael Owen <michael@adl-crm.uk>, 2018
     *
     * ADL CRM makes use of the following third party open sourced software/tools:
     *  DataTables - https://github.com/DataTables/DataTables
     *  EasyAutocomplete - https://github.com/pawelczak/EasyAutocomplete
     *  PHPMailer - https://github.com/PHPMailer/PHPMailer
     *  ClockPicker - https://github.com/weareoutman/clockpicker
     *  fpdf17 - http://www.fpdf.org
     *  summernote - https://github.com/summernote/summernote
     *  Font Awesome - https://github.com/FortAwesome/Font-Awesome
     *  Bootstrap - https://github.com/twbs/bootstrap
     *  jQuery UI - https://github.com/jquery/jquery-ui
     *  Google Dev Tools - https://developers.google.com
     *  Twitter API - https://developer.twitter.com
     *  Webshim - https://github.com/aFarkas/webshim/releases/latest
     *
     */

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    define("BASE_URL", (filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS)));


$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);
?>
<!DOCTYPE html>
<html lang="en">
<title>ADL | Real Time Report</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="/Wallboards/css/Connex.css" type="text/css"/>
<link rel="stylesheet" href="/Wallboards/css/bootstrap-3.3.5-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="/Wallboards/css/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="/Wallboards/css/bootstrap-3.3.5-dist/css/bootstrap.css">
<script type="text/javascript" src="/Wallboards/css/fontawesome/svg-with-js/js/fontawesome-all.js"></script>
<style>
    .blinking{
        animation:blinkingText 1.2s infinite;
    }
    @keyframes blinkingText{
        0%{     color: #ffffff;    }
        49%{    color: #ffffff; }
        60%{    color: transparent; }
        99%{    color:transparent;  }
        100%{   color: #ffffff;    }
    }

</style>
<script type="text/javascript" src="/js/jquery/jquery-3.0.0.min.js"></script>
<script>
    function refresh_div() {
        jQuery.ajax({
            url: ' ',
            type: 'POST',
            success: function (results) {
                jQuery(".contain-to-grid").html(results);
            }
        });
    }

    t = setInterval(refresh_div, 3000);
</script>
</head>
<body>
<?php

if(isset($EXECUTE) && $EXECUTE == 1) {
    require_once(BASE_URL . '/includes/CONNEX_PDO_CON_NEW.php');
} else {

    require_once(BASE_URL . '/includes/CONNEX_PDO_CON.php');

}

?>

<div class="contain-to-grid">

    <?php

        $Closer_query = $pdo->prepare("SELECT 
    live_agents.status,
    full_name,
    TIMEDIFF(CURRENT_TIMESTAMP, last_call_time) AS Time,
    TIMEDIFF(CURRENT_TIMESTAMP, last_call_finish) AS logTime,
    live_agents.lead_id,
    live_agents.uniqueid,
    live_agents.comments,
    sub_status,
    dead_epoch,
    dead_sec,
    event_time,
    TIMEDIFF(CURRENT_TIMESTAMP, event_time) AS callFinish
FROM
    live_agents
        JOIN
    users ON live_agents.user = users.user
        JOIN
    agent_log ON live_agents.agent_log_id = agent_log.agent_log_id
WHERE
    live_agents.campaign_id = 1002
ORDER BY live_agents.status , callFinish DESC
LIMIT 10");

        echo "<table id='main2' border='1' align=\"center\">";

        $Closer_query->execute();
        if ($Closer_query->rowCount() > 0) {
            while ($result = $Closer_query->fetch(PDO::FETCH_ASSOC)) {

                if (isset($result['status'])) {
                    $status = $result['status'];
                }
                if (isset($result['uniqueid'])) {
                    $uniqueid = $result['uniqueid'];
                }
                /* if (isset($result['phone_number'])) {
                     $phone_number = $result['phone_number'];
                 }*/
                if (isset($result['campaign_id'])) {
                    $campaign_id = $result['campaign_id'];
                }
                if (isset($result['lead_id'])) {
                    $lead_id = $result['lead_id'];
                }
                if (isset($result['full_name'])) {
                    $full_name = $result['full_name'];
                }

                if ($status == 'INCALL' || $status == 'MANUAL') {
                    $Time = $result['Time'];
                } else {
                    $Time = $result['callFinish'];
                }

                if (isset($result['sub_status'])) {
                    $subStatus = $result['sub_status'];
                }

                $time2 = $result['callFinish'];
                $time = $result['Time'];
                $time3 = $result['logTime'];

                switch ($status) {
                    case("READY"):
                        $class2 = 'status_READY12';
                        break;
                    case("INCALL"):
                        if ($result['comments'] == 'MANUAL') {
                            $class2 = 'status_MANUAL';
                        } else {
                            $class2 = 'status_INCALL12';
                        }
                        /* if ($phone_number <= '0') {
                             $status = 'DEAD';
                             $class2 = 'status_DEAD2';
                         }*/
                        break;
                    case("PAUSED"):
                        $class2 = 'status_PAUSED12';
                        if ($subStatus == 'LAGGED') {
                            $class = 'status_PAUSED1';
                        }
                        break;
                    case("QUEUE"):
                        $class2 = 'status_QUEUE2';
                        break;
                    default:
                        $class2 = 'status_READY12';
                        break;
                }
                echo "<td class='$class2'>$full_name <br>$Time</td>";
            }
        }
        echo "</table>";

        //END OF CLOSERS

        $lead_for_query = $pdo->prepare("SELECT 
    campaign_id, 
    lead_id, 
    phone_number, 
    status
FROM
    inbound_log
WHERE
    DATE(call_date) >= CURDATE()
        AND status = 'QUEUE'
");

        echo "<table id='main2' border='1' align=\"center\" cellspacing=\"5\">";

        $lead_for_query->execute();
        if ($lead_for_query->rowCount() > 0) {
            while ($result = $lead_for_query->fetch(PDO::FETCH_ASSOC)) {

                $leadFor = strtoupper("Lead FOR $result[campaign_id]");

                echo '<tr class="status_LEAD">';
                echo "<td>$leadFor</td>";
                echo "</tr>";

                echo "</table>";
            }
        }

        //END OF LEAD FOR CLOSER

        $query = $pdo->prepare("SELECT 
    live_agents.status,
    full_name,
    TIMEDIFF(CURRENT_TIMESTAMP, last_call_time) AS Time,
    TIMEDIFF(CURRENT_TIMESTAMP, last_call_finish) AS callFinish,
    live_agents.lead_id,
    live_agents.uniqueid,
    live_agents.comments,
    sub_status,
    dead_epoch,
    dead_sec,
    event_time,
    phone_number,
    TIMEDIFF(CURRENT_TIMESTAMP, event_time) AS Time2
FROM
    live_agents
        JOIN
    users ON live_agents.user = users.user
        JOIN
    agent_log ON live_agents.agent_log_id = agent_log.agent_log_id
    LEFT JOIN outbound_log on outbound_log.uniqueid = live_agents.uniqueid
WHERE
    live_agents.campaign_id IN (1001)
ORDER BY live_agents.status , last_call_time
LIMIT 70");

        echo "<table id='users'>";

        $query->execute();
        if ($query->rowCount() > 0) {
            while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

                if (isset($result['status'])) {
                    $status = $result['status'];
                }
                if (isset($result['Time'])) {
                    $Time = $result['Time'];
                }

                if (isset($result['lead_id'])) {
                    $lead_id = $result['lead_id'];
                }

                if (isset($result['comments'])) {
                    $comments = $result['comments'];
                }

                if ($status == 'INCALL' || $status == 'MANUAL') {
                    $Time = $result['Time'];
                } else {
                    $Time = $result['callFinish'];
                }

                if (isset($result['sub_status'])) {
                    $subStatus = $result['sub_status'];
                }

                if (isset($result['dead_epoch'])) {
                    $deadEpoch = $result['dead_epoch'];
                } else {
                    $deadEpoch = null;
                }

                switch ($result['status']) {
                    case("READY"):
                        $class = 'status_READY';
                        if ($Time < '00:00:98') {
                            $result['status'] = 'READY';
                            $class = 'status_READY10';
                        } elseif ($Time >= '00:00:99') {
                            $result['status'] = 'READY';
                            $class = 'status_READY1';
                        } elseif ($Time >= '00:01:99') {
                            $result['status'] = 'READY';
                            $class = 'status_READY5';
                        }
                        break;
                    case("INCALL"):
                        $class = 'status_INCALL';
                        /* if ($result['phone_number'] <= '0') {
                             $result['status'] = 'DEAD';
                             $class = 'status_DEAD';
                         }*/
                        if ($result['comments'] == 'INBOUND') {
                            $result['status'] = 'TRANSFER';
                            $class = 'status_trans';
                        } elseif ($Time < '00:00:98') {
                            $result['status'] = 'INCALL';
                            $class = 'status_INCALL10';
                        } elseif ($Time >= '00:00:99' && $Time < '00:02:99') {
                            $result['status'] = 'INCALL';
                            $class = 'status_INCALL1';
                        } elseif ($Time >= '00:02:99') {
                            $result['status'] = 'INCALL';
                            $class = 'status_INCALL5';
                        }
                        if (!is_null($deadEpoch)) {
                            $status = 'DEAD';
                            $class = 'status_DEAD';
                        } else {
                            $status = 'INCALL';
                        }
                        break;
                    case("PAUSED"):
                        if ($lead_id > 0 && $comments = ' ') {
                            $result['status'] = 'DISPO';
                            $class = 'status_DISPO';
                            $status = 'DISPO';
                        } elseif ($Time < '00:00:10') {
                            $class = 'status_PAUSED1';
                        } elseif ($Time >= '00:00:10' && $Time < '00:04:99') {
                            $class = 'status_PAUSED10';
                        } elseif ($Time > '00:04:99') {
                            $class = 'status_PAUSED5';
                        } else {
                            $class = 'status_PAUSED';
                        }
                        break;
                    case("QUEUE"):
                        $class = 'status_QUEUE';
                        break;
                    default:
                        $class = 'status_READY';
                        break;
                }

                ?>
                <tr class="<?php echo $class;?>">
                    <td><strong><span <?php if(substr($Time, -5) >= 7 && $status == 'INCALL') { echo "class='blinking'"; } ?>><?php echo $result['full_name']; ?></span></strong></td>
                    <td><span <?php if(substr($Time, -5) >= 7 && $status == 'INCALL') { echo "class='blinking'"; } ?>><?php echo $status; ?></span></td>
                    <td><span <?php if(substr($Time, -5) >= 7 && $status == 'INCALL') { echo "class='blinking'"; } ?>><?php echo substr($Time, -5); ?></span></td>
                </tr>

                <?php
            }
        }
    ?>
</div>
</body>
</html>
