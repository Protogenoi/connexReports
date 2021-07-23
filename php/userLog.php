<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define("BASE_URL", (filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS)));

require_once(BASE_URL . '/includes/CONNEX_PDO_CON.php');
//require_once(BASE_URL . '/includes/CONNEX_PDO_CON_NEW.php');

$query = $pdo->prepare("SELECT user_log_id, full_name, event, event_date, event_epoch, campaign_id FROM user_log JOIN users ON users.user = user_log.user where event_date >=CURDATE();");
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

                $sendToADL->setUserLogId($item['user_log_id']);
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
