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


    require_once(BASE_URL . '/includes/CONNEX_PDO_CON.php');

require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
require_once(BASE_URL . '/class/agentWallboard.php');

$sendToADL = new agentWallboard($adlPdo);

    $Closer_query = $pdo->prepare("SELECT 
    live_agents.status,
    full_name,
    TIMEDIFF(CURRENT_TIMESTAMP, last_call_time) AS Time,
    TIMEDIFF(CURRENT_TIMESTAMP, last_call_finish) AS logTime,
    live_agents.lead_id,
    live_agents.uniqueid,
    comments,
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
WHERE live_agents.campaign_id = '9996' ORDER BY live_agents.status , callFinish DESC LIMIT 10");


    $Closer_query->execute();
    if ($Closer_query->rowCount() > 0) {
        while ($result = $Closer_query->fetch(PDO::FETCH_ASSOC)) {

            $callFinish = $result['callFinish'];

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


            $sendToADL->setColour($class2);
            $sendToADL->setAgent($full_name);
            $sendToADL->setStatus($status);
            $sendToADL->setCallFinish($callFinish);
            $sendToADL->setTime($Time);
            $sendToADL->setUserGroup('Closer');
            $sendToADL->sendAgentWallboardToADL();

        }
    }


    //END OF CLOSERS

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
    live_agents.campaign_id IN ('10','1300','1700','1702')
ORDER BY live_agents.status , last_call_time
LIMIT 70");

    $query->execute();
    if ($query->rowCount() > 0) {
        while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

            $callFinish = $result['callFinish'];

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

            $sendToADL->setColour($class);
            $sendToADL->setAgent($result['full_name']);
            $sendToADL->setStatus($status);
            $sendToADL->setCallFinish($callFinish);
            $sendToADL->setUserGroup('Agent');
            $sendToADL->setTime(substr($Time, -5));
            $sendToADL->sendAgentWallboardToADL();

        }
    }
