<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define("BASE_URL", (filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS)));

require_once(BASE_URL . '/includes/CONNEX_PDO_CON.php');
//require_once(BASE_URL . '/includes/CONNEX_PDO_CON_NEW.php');

$query = $pdo->prepare("SELECT full_name, COUNT(*) AS callCount, SUM(talk_sec) AS talkSec, min(event_time) AS firstCall, max(event_time) AS lastCall FROM agent_log LEFT JOIN users ON users.user = agent_log.user WHERE event_time >= CURDATE() AND lead_id != '' GROUP BY agent_log.user");
$query->execute();
if ($query->rowCount() > 0) {

    require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
    require_once(BASE_URL . '/class/callTimes.php');

    while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

        $fullName = $result['full_name'];
        $callCount = $result['callCount'];
        $talkSec = $result['talkSec'];

        $agentName = str_replace('-', ' ', $fullName);
        var_dump($result);
        $sendToADL = new callTimes($adlPdo);
        $sendToADL->setFullName($agentName);
        $sendToADL->setCallCount($callCount);
        $sendToADL->setTalkSec($talkSec);
        $sendToADL->setFirstCall($result['firstCall']);
        $sendToADL->setLastCall($result['lastCall']);
        $result = $sendToADL->sendCallTimesToADL();


    }
}
