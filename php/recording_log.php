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

define("BASE_URL", (filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS)));

$startTimeVar = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_SPECIAL_CHARS);

if (empty($startTimeVar)) {

    $startTimeVar = date('Y-m-d');

}

require_once(BASE_URL . '/includes/CONNEX_PDO_CON.php');
//require_once(BASE_URL . '/includes/CONNEX_PDO_CON_NEW.php');
#AND status IN('XFER','SALE','CALLBK')
$query = $pdo->prepare("SELECT campaign_id, recording_id, server_ip, extension, start_time, start_epoch, end_time, end_epoch, length_in_sec, length_in_min, filename, location, lead_id, user, uniqueid, list_id, status, phone_number 
FROM recording_log WHERE DATE(start_time) =:startTime
");
$query->bindParam(':startTime', $startTimeVar);
$query->execute();
if ($query->rowCount() > 0) {

    $start_time = microtime(true);

    require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
    require_once(BASE_URL . '/class/recordingLog.php');

    while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

        $sendToADL = new recordingLog($adlPdo);
        $sendToADL->setCampaignId($result['campaign_id']);
        $sendToADL->setRecordingId($result['recording_id']);
        $sendToADL->setServerIp($result['server_ip']);
        $sendToADL->setExtension($result['extension']);
        $sendToADL->setStartTime($result['start_time']);
        $sendToADL->setStartEpoch($result['start_epoch']);
        $sendToADL->setEndTime($result['end_time']);
        $sendToADL->setEndEpoch($result['end_epoch']);
        $sendToADL->setLengthInSec($result['length_in_sec']);
        $sendToADL->setLengthInMin($result['length_in_min']);
        $sendToADL->setFilename($result['filename']);
        $sendToADL->setLocation($result['location']);
        $sendToADL->setLeadId($result['lead_id']);
        $sendToADL->setUser($result['user']);
        $sendToADL->setUniqueid($result['uniqueid']);
        $sendToADL->setListId($result['list_id']);
        $sendToADL->setStatus($result['status']);
        $sendToADL->setPhoneNumber($result['phone_number']);
        $result = $sendToADL->toADL();


    }

    $end_time = microtime(true);
    $execution_time = ($end_time - $start_time);
    echo " Execution time: " . $execution_time . " seconds";

}



