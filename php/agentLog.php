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

$start_time = microtime(true);

define("BASE_URL", (filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS)));

require_once(BASE_URL . '/includes/CONNEX_PDO_CON.php');

$query = $pdo->prepare("SELECT full_name FROM user_log JOIN users ON users.user = user_log.user where event_date >=CURDATE() GROUP BY full_name");
$query->execute();
if ($query->rowCount() > 0) {

    require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
    require_once(BASE_URL . '/class/agentLog.php');

    $getAgentLog = new agentLog($pdo);
    $sendToADL = new agentLog($adlPdo);

    while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

        $agentName = $result['full_name'];

        $getAgentLog->setFullName($agentName);
        $result = $getAgentLog->getTodayAgentLogByFullName();

        if(is_array($result)) {

            foreach ($result as $item):

                $agentName = str_replace('-', ' ', $item['full_name']);
#echo $item['status'] . "<br>";
            $sendToADL->setFullName($agentName);
            $sendToADL->setPauseSec($item['pause_sec']);
            $sendToADL->setUniqueid($item['uniqueid']);
            $sendToADL->setWaitSec($item['wait_sec']);
            $sendToADL->setTalkSec($item['talk_sec']);
            $sendToADL->setDispoSec($item['dispo_sec']);
            $sendToADL->setDeadSec($item['dead_sec']);
            $sendToADL->setStatus($item['status']);
            $sendToADL->setLeadId($item['lead_id']);
            $sendToADL->setEventTime($item['event_time']);
            $sendToADL->setAgentLogId($item['agent_log_id']);
                $result = $sendToADL->sendAgentLogToADL();

            endforeach;

        }


    }
}

$end_time = microtime(true);
$execution_time = ($end_time - $start_time);
echo " Execution time: " . $execution_time . " seconds";
