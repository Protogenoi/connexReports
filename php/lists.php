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
//require_once(BASE_URL . '/includes/CONNEX_PDO_CON_NEW.php');


require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
require_once(BASE_URL . '/class/lists.php');

$sendToADL = new lists($adlPdo);

$query = $pdo->prepare("SELECT list_id, list_name, active, campaign_id FROM lists WHERE campaign_id IN(1001,2001)");
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

            #var_dump($sendToADL);
            #echo "$result[list_id]<br>";
        }
    }
}

$end_time = microtime(true);
$execution_time = ($end_time - $start_time);
echo " Execution time: " . $execution_time . " seconds";

