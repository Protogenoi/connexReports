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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define("BASE_URL", (filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS)));


$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);


require_once(BASE_URL . '/includes/CONNEX_PDO_CON.php');
//require_once(BASE_URL . '/includes/CONNEX_PDO_CON_NEW.php');

require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
require_once(BASE_URL . '/class/callTimes.php');

$sendToADL = new callTimes($adlPdo);

$inboundCalls = 0;
$outboundCalls = 0;

$query = $pdo->prepare("SELECT count(*) AS callCount FROM outbound_log WHERE call_date >= CURDATE() AND campaign_id IN (1001)");
$query->execute();
if ($query->rowCount() > 0) {
    while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

        $inboundCalls = $result['callCount'];

    }
}


$query = $pdo->prepare("SELECT count(*) AS callbackCount, u.full_name
FROM list
LEFT JOIN users u on list.user = u.user
WHERE
      YEARWEEK(modify_date, 1) = YEARWEEK(CURDATE()) AND list.user
      IN (
          'ashleighwoodgate',
          'AlfredFlynn',
          'danielward',
          'GeraintMorgan',
          'kbarnett',
          'OliverMurphy',
          'rmichael'
          )
      AND status IN('CALLBK', 'CBHOLD') GROUP BY u.user");

$query->execute();

if ($query->rowCount() > 0) {
    while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

        if (!empty($result['callbackCount']) && $result['callbackCount'] >= 1) {

            #echo "name " . $result['full_name'] . " counts: " . $result['callbackCount'] . "<br>";

            $sendToADL->setCallbacks($result['callbackCount']);
            $sendToADL->setFullName($result['full_name']);
            $sendToADL->sendCallBacks();

        }
    }
}


$end_time = microtime(true);
$execution_time = ($end_time - $start_time);
echo " Execution time: " . $execution_time . " seconds";
