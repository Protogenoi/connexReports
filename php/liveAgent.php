<?php
/**
 * ------------------------------------------------------------------------
 *                               ADL CRM
 * ------------------------------------------------------------------------
 *
 * Copyright © 2021 ADL CRM All rights reserved.
 *
 * Unauthorised copying of this file, via any medium is strictly prohibited.
 * Unauthorised distribution of this file, via any medium is strictly prohibited.
 * Unauthorised modification of this code is strictly prohibited.
 *
 * Proprietary and confidential
 *
 * Written by Michael Owen <michael@adl-crm.uk>, 2018
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define("BASE_URL", (filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS)));


$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);


require_once(BASE_URL . '/includes/CONNEX_PDO_CON.php');

require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
require_once(BASE_URL . '/class/agentWallboard.php');

$sendToADL = new agentWallboard($adlPdo);

$query = $pdo->prepare("SELECT 
    users.full_name as 'Agent Name'
    ,users.user_id as 'id'
    ,live_agents.user_level
    #,live_agents.user
    #,live_agents.niqueid
    #,live_agents.erver_ip
    ,live_agents.sales
    #,live_agents.status
    #,live_agents.ring_callerid
    #,live_agents.random_id
    #,live_agents.ra_user
    #,live_agents.ra_extension
    #,live_agents.preview_lead_id
    #,live_agents.pause_code
    #,live_agents.outbound_autodial
    #,live_agents.on_hook_ring_time
    #,live_agents.on_hook_agent
    #,live_agents.manager_ingroup_set
    #,live_agents.live_agent_id
    #,live_agents.lead_id
    #,live_agents.last_update_time
    #,live_agents.last_state_change
    #,live_agents.last_inbound_call_time
    #,live_agents.last_inbound_call_finish
    #,live_agents.last_call_time
    #,live_agents.last_call_finish
    #,live_agents.external_update_fields_data
    #,live_agents.external_update_fields
    #,live_agents.external_transferconf
    #,live_agents.external_timer_action_seconds
    #,live_agents.external_timer_action_message
    #,live_agents.external_timer_action_destination
    #,live_agents.external_timer_action
    #,live_agents.external_status
    #,live_agents.external_recording
    #,live_agents.external_pause_code
    #,live_agents.external_pause
    #,live_agents.external_park
    #,live_agents.external_lead_id
    #,live_agents.external_ingroups
    #,live_agents.external_igb_set_user
    #,live_agents.external_hangup
    #,live_agents.external_dtmf
    #,live_agents.external_dial
    #,live_agents.external_blended
    #,live_agents.extension
    #,live_agents.conf_exten
    #,live_agents.comments
    #,live_agents.closer_campaigns
    #,live_agents.channel
    #,live_agents.campaign_weight
    #,live_agents.campaign_id
    #,live_agents.campaign_grade
    #,live_agents.calls_today
    #,live_agents.callerid
    #,live_agents.call_server_ip
    #,live_agents.agent_territories
    #,live_agents.agent_log_id
FROM live_agents JOIN users ON users.user = live_agents.user");
$query->execute();
if ($query->rowCount() > 0) {
    //echo '<pre>';
    $rows = $query->fetchAll(PDO::FETCH_ASSOC);
    //echo '</pre>';


    $listOfSkippedVariables = [
        "id",
        "test"
    ];

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Datatable</title>
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css"/>
    </head>
    <body>
    <table id="userTable">
        <thead>

        <?php

        if (!empty($rows)) {

            foreach ($rows[0] as $key => $value):
                if (!in_array($key, $listOfSkippedVariables)) {


                    ?>
                    <th><?php echo $key; ?></th>
                    <?php

                }

            endforeach;

        }
        ?>
        </thead>
        <tbody>
        <?php if (!empty($rows)) {
            foreach ($rows as $row): ?>

                <tr>
                    <?php foreach ($row as $key => $value) {

                        switch ($key):
                            case 'id':
                                break;
                            case 'Agent Name':
                                echo "<td><a href='https://web-assurafinancial.cnx1.cloud/Admin/agents/edit/?c=$row[id]' target='_blank'>$value</a></td>";
                                break;
                            default:
                                echo "<td>$value</td>";
                        endswitch;

                    } ?>
                </tr>

            <?php endforeach;
        } ?>
        </tbody>
    </table>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#userTable').DataTable();
        });
    </script>

    <pre>
    <?php

    //var_dump(get_defined_vars());

    ?>
</pre>
    </body>
    </html>
<?php } ?>
