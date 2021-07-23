<?php

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
