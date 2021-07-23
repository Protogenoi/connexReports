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
//require_once(BASE_URL . '/includes/CONNEX_PDO_CON_NEW.php');

require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
require_once(BASE_URL . '/class/inboundOutboundLog.php');

$sendToADL = new inboundOutboundLog($adlPdo);

$inboundCalls = 0;
$outboundCalls = 0;

$query = $pdo->prepare("SELECT count(*) AS callCount FROM outbound_log WHERE call_date >= CURDATE() AND campaign_id IN (1001)");
$query->execute();
if ($query->rowCount() > 0) {
    while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

        $inboundCalls = $result['callCount'];

    }
}


$query = $pdo->prepare("SELECT count(*) AS callCount FROM inbound_log WHERE call_date >= CURDATE()");

$query->execute();
if ($query->rowCount() > 0) {
    while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

        $outboundCalls = $result['callCount'];


    }
}

$totalCalls = $inboundCalls + $outboundCalls;

$sendToADL->setTotalCalls($totalCalls);
$sendToADL->insertCallCount();
