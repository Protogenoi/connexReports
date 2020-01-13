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
     * Written by michael <michael@adl-crm.uk>, 18/02/19 10:15
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
     */

    define("BASE_URL", (filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS)));

    $EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);

    if (isset($EXECUTE)) {
        if ($EXECUTE == '1') {

            header('Access-Control-Allow-Origin: http://localhost:8080');
            header("Content-type:application/json");

            require_once(BASE_URL . '/includes/CONNEX_PDO_CON.php');


            $query = $pdo->prepare("SELECT 
    live_agents.status AS agentStatus,
    full_name,
    TIMEDIFF(CURRENT_TIMESTAMP, last_call_time) AS agentTime,
    TIMEDIFF(CURRENT_TIMESTAMP, last_call_finish) AS callFinish,
    live_agents.lead_id,
    live_agents.uniqueid,
    live_agents.comments,
    sub_status,
    dead_epoch,
    dead_sec,
    event_time,
    phone_number,
    live_agents.campaign_id,
    TIMEDIFF(CURRENT_TIMESTAMP, event_time) AS agentTime2
FROM
    live_agents
        JOIN
    users ON live_agents.user = users.user
        JOIN
    agent_log ON live_agents.agent_log_id = agent_log.agent_log_id
        LEFT JOIN
    outbound_log ON outbound_log.uniqueid = live_agents.uniqueid
ORDER BY live_agents.status , last_call_time");
            $query->execute() or die(print_r($query->errorInfo(), true));
            json_encode($results = $query->fetchAll(PDO::FETCH_ASSOC));


            echo json_encode($results);

        }

    }
