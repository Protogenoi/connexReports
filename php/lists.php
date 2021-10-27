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


define("BASE_URL", (filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS)));


$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);


require_once(BASE_URL . '/includes/CONNEX_PDO_CON.php');
//require_once(BASE_URL . '/includes/CONNEX_PDO_CON_NEW.php');


require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
require_once(BASE_URL . '/class/lists.php');

$sendToADL = new lists($adlPdo);

$query = $pdo->prepare("SELECT list_id, list_name, active, campaign_id FROM lists WHERE campaign_id IN(1001)");
$query->execute();
if ($query->rowCount() > 0) {
    while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

        $query2 = $pdo->prepare("SELECT count(*) as totalRecords,SUM(called_count) AS totalDialled, entry_date FROM list WHERE list_id=:list_id");
        $query2->bindParam(':list_id', $result['list_id']);
        $query2->execute();
        if ($query2->rowCount() > 0) {
            $result2 = $query2->fetch(PDO::FETCH_ASSOC);

            $sendToADL->setCampaignId($result['campaign_id']);
            $sendToADL->setListId($result['list_id']);
            $sendToADL->setListName($result['list_name']);
            $sendToADL->setActive($result['active']);
            $sendToADL->setEntryDate($result2['entry_date']);
            $sendToADL->setTotalRecords($result2['totalRecords']);
            $sendToADL->setTotalDialled($result2['totalDialled']);
            $sendToADL->sendListstoADL();

            var_dump($sendToADL);
            echo '<hr>';
        }
    }
}

