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
    .container {
        width: 95%;
    }
</style>
<script type="text/javascript" src="/js/jquery/jquery-3.0.0.min.js"></script>
<script>
    function refresh_div() {
        jQuery.ajax({
            url: ' ',
            type: 'POST',
            success: function (results) {
                jQuery(".container").html(results);
            }
        });
    }

    t = setInterval(refresh_div, 4000);
</script>
</head>
<body>


<div class="container">


    <?php

    require_once(BASE_URL . '/includes/CONNEX_PDO_CON.php');


    $CLOSER_query = $pdo->prepare("SELECT 
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
    live_agents.campaign_id = '9996'
ORDER BY live_agents.status , callFinish DESC
LIMIT 10");

    echo "<table id='main2' cellspacing='0' cellpadding='10'>";

    $CLOSER_query->execute();
    if ($CLOSER_query->rowCount() > 0) {
        while ($result = $CLOSER_query->fetch(PDO::FETCH_ASSOC)) {

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

                switch ($full_name) {
                    case("Jade Cooper"):
                        $full_name = "Jade";
                        break;
                }
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
                    if (isset($subStatus) && $subStatus == 'LAGGED') {
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

            echo "<td class='$class2'><strong style='font-size: 35px;'>$full_name</strong><br>$Time</td>";

        }
    }
    echo "</table>"; ?>


    <?php

    $LEAD_query = $pdo->prepare("SELECT 
    campaign_id, 
    lead_id, 
    phone_number, 
    status
FROM
    inbound_log
WHERE
    DATE(call_date) >= CURDATE()
        AND status = 'QUEUE'");
    $LEAD_query->execute();
    if ($LEAD_query->rowCount() > 0) {
        while ($result = $LEAD_query->fetch(PDO::FETCH_ASSOC)) {

            ?>
            <div class="row">
                <div class="col-sm-12">
                    <strong>
                        <center><h1 style="color:white;"><i class="fa fa-arrow-circle-o-right"></i> LEAD
                                FOR <?php echo $result['campaign_id']; ?></h1></center>
                    </strong>
                </div>
            </div>

            <?php

        }
    }


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
    live_agents.campaign_id != '9996' AND live_agents.status != 'PAUSED'
ORDER BY live_agents.status , last_call_time
LIMIT 70");

    $dyn_table = '<table cellspacing="0"  cellpadding="10" id="boo">';

    $query->execute();
    if ($query->rowCount() > 0) {
        $i = 0;
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
                    $PAUSE_CODE_I_CLASS = "fa-clock";
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
                    $PAUSE_CODE_I_CLASS = "fa-phone";
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
                    $PAUSE_CODE_I_CLASS = "fa-pause";
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
                    $PAUSE_CODE_I_CLASS = "fa-clock";
                    break;
                default:
                    $class = 'status_READY';
                    break;
            }

            /*if(!isset($PAUSE_CODE_I_CLASS)) {

                throw new Exception("Unknown Stats: '.$result[status] . ' '.$result[full_name]");

            }*/

            if ($i % 5 == 0) {
                $dyn_table .= '<tr><td class=' . $class . '><strong style="font-size: 30px;">' . $result['full_name'] . '</strong><br><i class="fa ' . $PAUSE_CODE_I_CLASS . '"></i> ' . substr($Time, -5) . '</td>';
            } else {
                $dyn_table .= '<td class=' . $class . '><strong style="font-size: 30px;">' . $result['full_name'] . ' </strong><br><i class="fa ' . $PAUSE_CODE_I_CLASS . '"></i> ' . substr($Time, -5) . '</td>';
            }
            $i++;
        }
        $dyn_table .= '</tr></table>';
    }
    echo $dyn_table;

    $PAUSEquery = $pdo->prepare("SELECT 
    COUNT(live_agents.status) AS LIVE_AGENT_ID
FROM
    live_agents
        JOIN
    users ON live_agents.user = users.user
        JOIN
    agent_log ON live_agents.agent_log_id = agent_log.agent_log_id
        LEFT JOIN
    outbound_log ON outbound_log.uniqueid = live_agents.uniqueid
WHERE
    live_agents.campaign_id != '9996'
        AND live_agents.status = 'PAUSED'");
    $PAUSEquery->execute();
    $PAUSEresult = $PAUSEquery->fetch(PDO::FETCH_ASSOC);

    $PAUSEDCOUNT = $PAUSEresult['LIVE_AGENT_ID'];

    $PAUSED_AGENTS_query = $pdo->prepare("
SELECT 
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
    live_agents.campaign_id != '9996'
    AND live_agents.status = 'PAUSED'
ORDER BY live_agents.status , last_call_time
LIMIT 70");


    $PAUSE_TABLE = '<table cellspacing="0"  cellpadding="10" id="boo">';

    $PAUSED_AGENTS_query->execute();
    if ($PAUSED_AGENTS_query->rowCount() > 0) {

        ?>

        <div class="row">
            <div class="col-sm-12">
                <strong>
                    <center><h2 style="color:white;">[ <i class="fa fa-pause"></i> <?php echo $PAUSEDCOUNT; ?>
                            PAUSED AGENT<?php if ($PAUSEDCOUNT >= '2') {
                                echo "S";
                            } ?> ]</h2></center>
                </strong>
            </div>
        </div>

        <?php

        $ii = 0;
        while ($result = $PAUSED_AGENTS_query->fetch(PDO::FETCH_ASSOC)) {

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
                case("PAUSED"):
                    $PAUSE_CODE_I_CLASS = "fa-pause";
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
            if ($ii % 5 == 0) {
                $PAUSE_TABLE .= '<tr><td class=' . $class . '><strong style="font-size: 30px;">' . $result['full_name'] . '</strong><br><i class="fa ' . $PAUSE_CODE_I_CLASS . '"></i> ' . $Time . '</td>';
            } else {
                $PAUSE_TABLE .= '<td class=' . $class . '><strong style="font-size: 30px;">' . $result['full_name'] . ' </strong><br><i class="fa ' . $PAUSE_CODE_I_CLASS . '"></i> ' . $Time . '</td>';
            }
            $ii++;
        }
        $PAUSE_TABLE .= '</tr></table>';
    }
    echo $PAUSE_TABLE;

    ?>
</div>
</body>
</html>
