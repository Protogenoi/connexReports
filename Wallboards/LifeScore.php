<!DOCTYPE html>
<html lang="en">
    <title>ADL | Real Time Report</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="imagetoolbar" content="no" />
    <link rel="stylesheet" href="/styles/realtimereport.css" type="text/css" />
    <style>
        .status_piltrans {color: white; background: #551A8B; }
        .blink_me {
            animation: blinker 1s linear infinite;
        }

        @keyframes blinker {  
            50% { opacity: 0; }
        }
    </style>
    <script type="text/javascript" language="javascript" src="/js/jquery/jquery-3.0.0.min.js"></script>
    <script>
        function refresh_div() {
            jQuery.ajax({
                url: ' ',
                type: 'POST',
                success: function (results) {
                    jQuery(".container").html(results);
                }
            });
        }

        t = setInterval(refresh_div, 10000);
    </script>
</head>
<body>
    <?php include("../includes/DIALLER_PDO_CON.php"); ?>

    <div class="container">
        <?php
        $Closer_query = $TRB_DB_PDO->prepare("SELECT 
    vicidial_live_agents.user,
    vicidial_live_agents.lead_id,
    vicidial_live_agents.pause_code,
    vicidial_live_agents.comments,
    vicidial_live_agents.status,
    vicidial_auto_calls.phone_number
FROM
    vicidial_live_agents
    LEFT JOIN vicidial_auto_calls on vicidial_live_agents.lead_id = vicidial_auto_calls.lead_id
WHERE
    vicidial_live_agents.campaign_id = '25'
ORDER BY vicidial_live_agents.status ASC , last_state_change
;");

        echo "<table id='main2' border='1' align=\"center\">";

        $Closer_query->execute();
        if ($Closer_query->rowCount() > 0) {
            while ($result = $Closer_query->fetch(PDO::FETCH_ASSOC)) {

                if (isset($result['status'])) {
                    $status = $result['status'];
                }
                if (isset($result['uniqueid'])) {
                    $uniqueid = $result['uniqueid'];
                }
                if (isset($result['phone_number'])) {
                    $phone_number = $result['phone_number'];
                }
                if (isset($result['campaign_id'])) {
                    $campaign_id = $result['campaign_id'];
                }
                if (isset($result['lead_id'])) {
                    $lead_id = $result['lead_id'];
                }
                if (isset($result['user'])) {
                    $full_name = $result['user'];
                }

                switch ($status) {
                    case("READY"):
                        $class2 = 'status_READY12';
                        break;
                    case("INCALL"):
                        $class2 = 'status_INCALL12';
                        if ($phone_number <= '0') {
                            $status = 'DEAD';
                            $class2 = 'status_DEAD2';
                        }
                        break;
                    case("PAUSED"):
                        $class2 = 'status_PAUSED12';
                        break;
                    case("QUEUE"):
                        $class2 = 'status_QUEUE2';
                        break;
                    default:
                        $class2 = 'status_READY12';
                        break;
                }
                echo "<td class='$class2'>$full_name</td>";
            }
        }
        echo "</table>";

        $lead_for_query = $TRB_DB_PDO->prepare("SELECT 
    vicidial_users.full_name,
    vicidial_auto_calls.status,
    vicidial_auto_calls.campaign_id
FROM
    vicidial_auto_calls
        JOIN
    vicidial_list ON vicidial_auto_calls.lead_id = vicidial_list.lead_id
        JOIN
    vicidial_users ON vicidial_users.user = vicidial_list.user
WHERE
    vicidial_auto_calls.status = 'live'
        AND vicidial_auto_calls.call_type = 'IN'
        AND vicidial_auto_calls.campaign_id IN ('Richard' , 'Kyle',
        'Sarah',
        'Gavin',
        'James',
        'Carys',
        'Mike',
        'Hayley')");

        echo "<table id='main2' border='1' align=\"center\" cellspacing=\"5\">";

        $lead_for_query->execute();
        if ($lead_for_query->rowCount() > 0) {
            while ($result = $lead_for_query->fetch(PDO::FETCH_ASSOC)) {

                switch ($result['status']) {
                    case("LIVE"):
                        $class = 'status_LEAD';
                        if ($result['status'] == 'LIVE') {
                            $result['status'] = 'LEAD';
                            $class = 'status_LEAD';
                        }
                        break;
                }
                echo '<tr class=' . $class . '>';
                echo "<td>" . $result['full_name'] . " " . $result['status'] . " FOR " . $result['campaign_id'] . "</td>";
                echo "</tr>";

                echo "</table>";
            }
        }


        $calls_query = $TRB_DB_PDO->prepare("SELECT 
    status
FROM
    vicidial_auto_calls
WHERE
    status = 'live' AND call_type = 'OUT'");

        echo "<table border='1' align=\"center\" cellspacing=\"5\">";

        $calls_query->execute();
        if ($calls_query->rowCount() > 0) {
            while ($result = $calls_query->fetch(PDO::FETCH_ASSOC)) {

                switch ($result['status']) {
                    case("LIVE"):
                        $class = 'status_LEAD';
                        if ($result['status'] = 'LIVE') {
                            $result['status'] = 'CALL WAITING';
                            $class = 'status_LEAD';
                        }
                        break;
                }
                echo '<tr class=' . $class . '>';
                echo "<td>" . $result['status'] . "</td>";
                echo "</tr>";
            }
        }
        echo "</table>";

        $query = $TRB_DB_PDO->prepare("SELECT 
    vicidial_users.full_name,
    vicidial_live_agents.comments,
    vicidial_auto_calls.phone_number,
    vicidial_live_agents.status,
    vicidial_live_agents.lead_id,
    vicidial_live_agents.pause_code,
    vicidial_live_agents.uniqueid,
    TIMEDIFF(CURRENT_TIMESTAMP,
            vicidial_live_agents.last_state_change) AS Time
FROM
    vicidial_live_agents
        JOIN
    vicidial_users ON vicidial_live_agents.user = vicidial_users.user
        LEFT JOIN
    vicidial_auto_calls ON vicidial_live_agents.lead_id = vicidial_auto_calls.lead_id
WHERE
    vicidial_live_agents.campaign_id IN ('10' , '51','50')
GROUP BY vicidial_users.full_name
ORDER BY vicidial_live_agents.status , last_state_change");
        echo "<table id='users' border='1' align=\"center\" cellspacing=\"5\">";

        $query->execute();
        if ($query->rowCount() > 0) {
            while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

                if (isset($result['status'])) {
                    $status = $result['status'];
                }
                if (isset($result['Time'])) {
                    $Time = $result['Time'];
                }

                if (isset($result['lead_id'])) {
                    $lead_id = $result['lead_id'];
                }


                switch ($result['status']) {
                    case("READY"):
                        $class = 'status_READY';
                        if ($Time < '00:00:98') {
                            $result['status'] = 'READY';
                            $class = 'status_READY10';
                        } elseif ($Time >= '00:00:99') {
                            $result['status'] = 'READY';
                            $class = 'status_READY1';
                        } elseif ($Time >= '00:01:99') {
                            $result['status'] = 'READY';
                            $class = 'status_READY5';
                        }
                        break;
                    case("INCALL"):
                        $class = 'status_INCALL';
                        if ($result['uniqueid'] == '0') {
                            $result['status'] = 'MANUAL';
                            $class = 'status_MANUAL';
                        }
                        if ($result['phone_number'] <= '0') {
                            $result['status'] = 'DEAD';
                            $class = 'status_DEAD';
                        }
                        if ($result['comments'] == 'INBOUND') {
                            $result['status'] = 'TRANSFER';
                            $class = 'status_trans';
                        } elseif ($Time < '00:00:98' && $result['uniqueid'] > '1') {
                            $result['status'] = 'INCALL';
                            $class = 'status_INCALL10';
                        } elseif ($Time >= '00:00:99' && $Time < '00:02:99' && $result['uniqueid'] > '1') {
                            $result['status'] = 'INCALL';
                            $class = 'status_INCALL1';
                        } elseif ($Time >= '00:02:99' && $result['uniqueid'] > '1') {
                            $result['status'] = 'INCALL';
                            $class = 'status_INCALL5';
                        }
                        break;
                    case("PAUSED"):

                        if ($lead_id <> '0') {
                            $result['status'] = 'DISPO';
                            $result['pause_code']='DISPO';
                            $class = 'status_DISPO';
                        }
                        elseif ($result['pause_code'] == 'Toilet' && $Time > '00:03:99') {
                            $result['status'] = 'MIA';
                            $class = 'status_AWOL';
                        } elseif ($result['pause_code'] == '1hr' && $Time > '00:59:99') {
                            $result['status'] = 'LATE';
                            $class = 'status_LATE';
                        } elseif ($result['pause_code'] == '10min' && $Time > '00:10:01') {
                            $result['status'] = 'LATE';
                            $class = 'status_LATE';
                        } elseif ($result['pause_code'] == 'Other' && $Time > '00:02:99') {
                            $result['status'] = 'MIA';
                            $class = 'status_AWOL';
                        } elseif ($result['pause_code'] == 'Login' && $Time > '00:00:99') {
                            $result['status'] = 'MIA';
                            $class = 'status_AWOL';
                        } elseif ($Time < '00:00:10') {
                            $result['status'] = 'PAUSED';
                            $class = 'status_PAUSED10';
                        } elseif ($Time >= '00:00:10' && $Time <= '00:01:99') {
                            $result['status'] = 'PAUSED';
                            $class = 'status_PAUSED1';
                        } elseif ($Time >= '00:02:00') {
                            $result['status'] = 'PAUSED';
                            $class = 'status_PAUSED5';
                        }
                        else {
                           $class = 'status_PAUSED'; 
                        }
                            
                        break;
                    case("QUEUE"):
                        $class = 'status_QUEUE';
                        break;
                    default:
                        $class = 'status_READY';
                        break;
                }
                echo '<tr class=' . $class . '>';
                echo "<td width=40%>" . $result['full_name'] . "</td>";
                echo "<td width=10%>" . $result['status'] . "</td>";
                echo "<td width=10%>" . $result['pause_code'] . "</td>";
                echo "<td width=10%>" . $result['Time'] . "</td>";
                echo "</tr>";
            }
        }
        ?>
    </div>
</body>
</html>

