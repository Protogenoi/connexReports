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

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);

require_once(BASE_URL . '/includes/CONNEX_PDO_CON.php');

require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
require_once(BASE_URL . '/class/inboundOutboundLog.php');

$sendToADL = new inboundOutboundLog($adlPdo);


$query = $pdo->prepare("SELECT list_id, closecallid, lead_id, campaign_id, call_date, length_in_sec, status, phone_number, user, comments, term_reason, called_count
FROM inbound_log WHERE call_date >= CURDATE()");
$query->execute();
if ($query->rowCount() > 0) {
    while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

        $sendToADL->setClosecallid($result['closecallid']);
        $sendToADL->setListId($result['list_id']);
        $sendToADL->setLeadId($result['lead_id']);
        $sendToADL->setCampaignId($result['campaign_id']);
        $sendToADL->setCallDate($result['call_date']);
        $sendToADL->setLengthInSec($result['length_in_sec']);
        $sendToADL->setStatus($result['status']);
        $sendToADL->setPhoneNumber($result['phone_number']);
        $sendToADL->setUser($result['user']);
        $sendToADL->setComments($result['comments']);
        $sendToADL->setTermReason($result['term_reason']);
        $sendToADL->setCalledCount($result['called_count']);
        $sendToADL->sendInboundLogToADL();

    }
}

$end_time = microtime(true);
$execution_time = ($end_time - $start_time);
echo " Execution time: " . $execution_time . " seconds";
