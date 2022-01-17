<?php
/**
 * ------------------------------------------------------------------------
 *                               ADL CRM
 * ------------------------------------------------------------------------
 *
 * Copyright © 2022 ADL CRM All rights reserved.
 *
 * Unauthorised copying of this file, via any medium is strictly prohibited.
 * Unauthorised distribution of this file, via any medium is strictly prohibited.
 * Unauthorised modification of this code is strictly prohibited.
 *
 * Proprietary and confidential
 *
 * Written by Michael Owen <michael@adl-crm.uk>, 2022
 *
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$start_time = microtime(true);

define("BASE_URL", (filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS)));

require_once(BASE_URL . '/includes/CONNEX_PDO_CON.php');
//require_once(BASE_URL . '/includes/CONNEX_PDO_CON_NEW.php');

$query = $pdo->prepare("SELECT user_log_id, full_name, event, event_date, '' AS event_epoch, campaign_id FROM user_log JOIN users ON users.user = user_log.user where event_date >=CURDATE();");
$query->execute();
if ($query->rowCount() > 0) {

    require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
    require_once(BASE_URL . '/class/userLog.php');

    $getUserLog = new userLog($pdo);
    $sendToADL = new userLog($adlPdo);

    while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

        $agentName = $result['full_name'];

        $getUserLog->setFullName($agentName);
        $result = $getUserLog->getTodayUserLogByFullName();

        if(is_array($result)) {

            foreach ($result as $item):

                $agentName = str_replace('-', ' ', $item['full_name']);

                if ($agentName) {
                    $sendToADL->setUserLogId($item['user_log_id']);
                }
                $sendToADL->setFullName($agentName);
                $sendToADL->setEvent($item['event']);
                $sendToADL->setEventDate($item['event_date']);
                $sendToADL->setEventEpoch($item['event_epoch']);
                $sendToADL->setCampaignId($item['campaign_id']);
                $result = $sendToADL->sendUserLogToADL();

            endforeach;

        }


    }
}

$end_time = microtime(true);
$execution_time = ($end_time - $start_time);
echo " Execution time: " . $execution_time . " seconds";
