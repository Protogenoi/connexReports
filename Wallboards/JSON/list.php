<?php
/**
 * ------------------------------------------------------------------------
 *                               ADL CRM
 * ------------------------------------------------------------------------
 *
 * Copyright © 2019 ADL CRM All rights reserved.
 *
 * Unauthorised copying of this file, via any medium is strictly prohibited.
 * Unauthorised distribution of this file, via any medium is strictly prohibited.
 * Unauthorised modification of this code is strictly prohibited.
 *
 * Proprietary and confidential
 *
 * Written by michael <michael@adl-crm.uk>, 11/02/19 13:22
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
 *  toastr - https://github.com/CodeSeven/toastr
 *  Twilio - https://github.com/twilio
 *  SendGrid - https://github.com/sendgrid
 *
 */

    define("BASE_URL", (filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS)));

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);
$listID = filter_input(INPUT_GET, 'listID', FILTER_SANITIZE_NUMBER_INT);

if (isset($EXECUTE)) {
    if ($EXECUTE == '1') {

        require_once(BASE_URL . '/includes/CONNEX_PDO_CON.php');


        $query = $pdo->prepare("SELECT title, first_name, last_name, address1, address2, address3, postal_code, email, comments ,status, phone_number from list WHERE list_id=:LISTID LIMIT 10000");
        $query->bindParam(':LISTID', $listID, PDO::PARAM_INT);
        $query->execute() or die(print_r($query->errorInfo(), true));
        json_encode($results = $query->fetchAll(PDO::FETCH_ASSOC));


        echo json_encode($results);

    }

}