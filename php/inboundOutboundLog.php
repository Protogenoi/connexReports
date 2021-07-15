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

$query = $pdo->prepare("SELECT uniqueid, lead_id, list_id, campaign_id, call_date, length_in_sec, status, phone_number, user, comments, term_reason, alt_dial, called_count FROM outbound_log WHERE call_date >= CURDATE() AND campaign_id IN (1001, 1002)");
$query->execute();
if ($query->rowCount() > 0) {
    while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

        $sendToADL->setUniqueid($result['uniqueid']);
        $sendToADL->setLeadId($result['lead_id']);
        $sendToADL->setListId($result['list_id']);
        $sendToADL->setCampaignId($result['campaign_id']);
        $sendToADL->setCallDate($result['call_date']);
        $sendToADL->setLengthInSec($result['length_in_sec']);
        $sendToADL->setStatus($result['status']);
        $sendToADL->setPhoneNumber($result['phone_number']);
        $sendToADL->setUser($result['user']);
        $sendToADL->setComments($result['comments']);
        $sendToADL->setTermReason($result['term_reason']);
        $sendToADL->setAltDial($result['alt_dial']);
        $sendToADL->setCalledCount($result['called_count']);
        $sendToADL->sendOutboundLogToADL();

    }
}


$query = $pdo->prepare("SELECT closecallid, lead_id, campaign_id, call_date, length_in_sec, status, phone_number, user, comments, term_reason, called_count FROM inbound_log WHERE call_date >= CURDATE()");

$query->execute();
if ($query->rowCount() > 0) {
    while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

        $sendToADL->setClosecallid($result['closecallid']);
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
