<?php
define("BASE_URL", (filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS)));

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);
$date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$dispo = filter_input(INPUT_POST, 'dispo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$callTime = filter_input(INPUT_POST, 'callTime', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

require_once(BASE_URL . '/includes/CONNEX_PDO_CON.php');

$defaultDate = date('Y-m-d');

?>
<!DOCTYPE html>
<html lang="en">
<title>Connex | Call Recordings</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="/jquery-ui-1.11.4/jquery-ui.min.css"/>
<link href="/img/favicon.ico" rel="icon" type="image/x-icon"/>
<style>
    .loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url('http://www.downgraf.com/wp-content/uploads/2014/09/01-progress.gif?e44397') 50% 50% no-repeat rgb(249, 249, 249);
    }
    /* ----------------------------------------------REAL TIME REPORT-------------------------------------*/

    .status_READY {
        color: black;
        background: #ADD8E6;
    }

    .status_INCALL {
        color: black;
        background: #D8BFD8;
    }

    .status_QUEUE {
        color: black;
        background: #37FDFC;
    }

    .status_DISPO {
        color: black;
        background: yellow;
    }

    .status_DEAD {
        color: white;
        background: black;
    }

    .status_MANUAL {
        color: black;
        background: #ff9999;
    }

    .status_LEAD {
        color: white;
        background: #FFA500;
    }

    .status_ptrans {
        color: white;
        background: #FF0000;
        font-size: 250%;
    }

    .status_nathan_transfer {
        color: white;
        background: #FF0000;
        font-size: 250%;
    }

    .status_LATE {
        color: white;
        background: #FF0000;
    }

    .status_AWOL {
        color: white;
        background: #600000;
    }

    .status_trans {
        color: white;
        background: #109618;
    }

    .status_PAUSED {
        color: black;
        background: #F0E68C;
    }

    .status_PAUSED1 {
        color: black;
        background-color: yellow;
    }

    .status_PAUSED5 {
        color: white;
        background-color: #808000 !important;
    }

    .status_PAUSED12 {
        color: white;
        background: #16A53F;
        font-weight: bold;
        text-align: center;
    }

    .status_PAUSED10 {
        color: black;
        background-color: #F0E68C;
    }

    .status_DEAD {
        color: white;
        background: #000000;
    }

    .status_READY12 {
        color: white;
        background: #16A53F;
        font-weight: bold;
        text-align: center;
    }

    .status_INCALL12 {
        color: white;
        background: #B00004;
        font-weight: bold;
        text-align: center;
    }

    .status_QUEUE2 {
        color: white;
        background: #ff0000;
        text-align: center;
    }

    .status_DEAD2 {
        color: white;
        background: red;
        text-align: center;
    }

    .status_MANUAL2 {
        color: white;
        background: #ff9999;
    }

    .status_READY10 {
        color: black;
        background-color: #ADD8E6;
    }

    .status_READY1 {
        color: white;
        background-color: blue;
    }

    .status_READY5 {
        color: white;
        background-color: #191970;
    }

    .status_INCALL10 {
        color: black;
        background-color: #D8BFD8;
    }

    .status_INCALL1 {
        color: black;
        background-color: #EE82EE;
    }

    .status_INCALL5 {
        color: white;
        background-color: purple;
    }
</style>
</head>
<body>
<?php include("../includes/navbar.html"); ?>
<div class="loader"><h1>Loading...</h1></div>
<div class="container">
    <br>
    <div class="card">
        <h3 class="card-header">
            <i class="fa fa-headphones"></i> Call Recordings
            <button class="btn btn-default btn-sm pull-right" data-toggle="modal" data-target="#loggedInModal"><i class="fa fa-user"></i> Logged in Agents
            </button><a class="btn btn-default btn-sm pull-right" href="callRecordings.php"><i class="fa fa-random"></i> Random
                Calls
            </a>
            <a href='connexRecordings.php'>
                <button type="button" class="btn btn-default btn-sm pull-right"><i class="fa fa-history"></i> New
                    Search...
                </button>
            </a>
        </h3>

        <div class="card-block">
            <p class="card-text">

            <div class="col-12">

                <?php if(isset($bar) && $bar == 1){ ?>
                    <div class="alert alert-primary" role="alert">
                        <span class='alert-link'>Issue</span> fixed, call recordings should now work</div>

                <div class="alert alert-danger" role="alert">
                    <span class='alert-link'>Issue</span> with slave DB server Connex working on fix</div>

                    <?php } ?>

                <form action="connexRecordings.php?EXECUTE=1" method="post" id="searchform" autocomplete="off">

                    <div class="row">
                        <div class="col-sm">

                            <div class="form-group">
                                <input type="text" class="form-control" name="date" id="date" required
                                       value="<?php if (isset($date)) {
                                           echo $date;
                                       } else {
                                           echo $defaultDate;
                                       } ?>">
                            </div>

                        </div>

                        <div class="col-sm">
                            <div class="form-group">
                                <select name="user" id="user" class="form-control" required>
                                    <option value="All">All Agents</option>
                                    <?php

                                    $query = $pdo->prepare("SELECT 
    users.user, users.full_name, DATE(user_log.event_date) as event_date
FROM
    users
    LEFT JOIN user_log on user_log.user = users.user
WHERE
    users.user_group IN ('TeamKyle','TeamRich','TeamJames', 'TeamKyleRich',1701,1700,1703,1704) GROUP BY user_log.user ORDER BY users.full_name");
                                    $query->execute();
                                    if ($query->rowCount() > 0) {
                                        while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

                                            if ($result['event_date'] == $defaultDate) {
                                                $loggedIn = 1;
                                            } else {
                                                $loggedIn = 0;
                                            }

                                            ?>

                                            <option value="<?php echo $result['user']; ?>" <?php if ($user == $result['user']) {
                                                echo "selected";
                                            } ?>><?php echo $result['full_name'];
                                                if ($loggedIn == 1) {
                                                    echo "Logged In";
                                                } ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm">
                            <div class="form-group">
                                <select name="dispo" id="dispo" class="form-control">
                                    <option value="0">Filter by DISPO</option>
                                    <?php

                                    $query = $pdo->prepare("SELECT status FROM system_status WHERE selectable='Y'");
                                    $query->execute();
                                    if ($query->rowCount() > 0) {
                                        while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

                                            ?>

                                            <option value="<?php echo $result['status']; ?>" <?php if ($dispo == $result['status']) {
                                                echo "selected";
                                            } ?>><?php echo $result['status']; ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <select name="callTime" id="callTime" class="form-control">
                                    <option value="All">Filter talk time (mins)</option>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php if (isset($callTime) && $callTime == $i) {
                                            echo "selected";
                                        } ?> ><?php echo ">= $i"; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">

                                <button type="submit" name="submit" id="submit" value="Search" data-toggle="modal"
                                        data-target="#processing-modal" class="btn btn-primary btn-lg btn-block"><i
                                            class="fa  fa-search"></i> SEARCH
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <br>

                <?php
                if (isset($EXECUTE)) {

                    if ($user == 'All') {
                        $userFlag = 0;
                    } else {
                        $userFlag = 1;
                    }

                    if ($dispo == '0') {
                        $dispoFlag = 0;
                    } else {
                        $dispoFlag = 1;
                    }

                    if ($callTime == 'All') {
                        $callTimeFlag = 0;
                    } else {
                        $callTimeFlag = 1;
                    }

                    switch ($callTime):
                        case 1:
                            $callTime = 60;
                            $displayCallTime = ">= 1";
                            break;
                        case 2:
                            $callTime = 120;
                            $displayCallTime = ">= 2";
                            break;
                        case 3:
                            $callTime = 180;
                            $displayCallTime = ">= 3";
                            break;
                        case 4:
                            $callTime = 240;
                            $displayCallTime = ">= 4";
                            break;
                        case 5:
                            $callTime = 300;
                            $displayCallTime = ">= 5";
                            break;
                        default:
                            $callTime = 'All';
                            $displayCallTime = ">= 0";
                    endswitch;

                    if ($userFlag == 1 && $dispoFlag == 1 && $callTimeFlag == 1) { // ALL FILTERS
//echo "ALL FILTERS | User: $userFlag  | Dispo: $dispoFlag | Time: $callTimeFlag";
                        $query = $pdo->prepare("SELECT 
    lead_id, full_name, phone_number, location, status
FROM
    recording_log
        JOIN
    users ON users.user = recording_log.user
WHERE
        DATE(start_time) =:DATE AND YEAR(start_time) = '2020'
        AND user_group IN ('TeamKyle','TeamRich','TeamJames', 'TeamKyleRich',1701,1700,1703,1704) AND status=:DISPO AND length_in_sec >=:callTime
AND recording_log.user=:USER
ORDER BY recording_id
LIMIT 100");
                        $query->bindParam(':DATE', $date, PDO::PARAM_STR);
                        $query->bindParam(':USER', $user, PDO::PARAM_STR);
                        $query->bindParam(':DISPO', $dispo, PDO::PARAM_STR);
                        $query->bindParam(':callTime', $callTime, PDO::PARAM_INT);

                    } elseif ($userFlag == 1 && $dispoFlag == 0 && $callTimeFlag == 0) { // SET USER ONLY
//echo "SET USER ONLY | User: $userFlag  | Dispo: $dispoFlag | Time: $callTimeFlag";
                        $query = $pdo->prepare("SELECT 
    lead_id, full_name, phone_number, location, status
FROM
    recording_log
        JOIN
    users ON users.user = recording_log.user
WHERE
        DATE(start_time) =:DATE AND YEAR(start_time) = '2020'
        AND user_group IN ('TeamKyle','TeamRich','TeamJames', 'TeamKyleRich',1701,1700,1703,1704)
AND recording_log.user=:USER
ORDER BY recording_id
LIMIT 100");
                        $query->bindParam(':DATE', $date, PDO::PARAM_STR);
                        $query->bindParam(':USER', $user, PDO::PARAM_STR);

                    } elseif ($userFlag == 1 && $dispoFlag == 1 && $callTimeFlag == 0) { // SET USER AND DISPO ONLY
//echo "SET USER AND DISPO ONLY | User: $userFlag  | Dispo: $dispoFlag | Time: $callTimeFlag";
                        $query = $pdo->prepare("SELECT 
    lead_id, full_name, phone_number, location, status
FROM
    recording_log
        JOIN
    users ON users.user = recording_log.user
WHERE
        DATE(start_time) =:DATE AND YEAR(start_time) = '2020'
        AND user_group IN ('TeamKyle','TeamRich','TeamJames', 'TeamKyleRich',1701,1700,1703,1704) AND status=:DISPO
AND recording_log.user=:USER
ORDER BY recording_id
LIMIT 100");
                        $query->bindParam(':DATE', $date, PDO::PARAM_STR);
                        $query->bindParam(':USER', $user, PDO::PARAM_STR);
                        $query->bindParam(':DISPO', $dispo, PDO::PARAM_STR);

                    } elseif ($userFlag == 1 && $dispoFlag == 0 && $callTimeFlag == 1) { // SET USER AND CALL TIME ONLY
//echo "SET USER AND CALL TIME ONLY | User: $userFlag  | Dispo: $dispoFlag | Time: $callTimeFlag";
                        $query = $pdo->prepare("SELECT 
    lead_id, full_name, phone_number, location, status
FROM
    recording_log
        JOIN
    users ON users.user = recording_log.user
WHERE
        DATE(start_time) =:DATE AND YEAR(start_time) = '2020' AND length_in_sec >=:callTime AND recording_log.user=:USER
        AND user_group IN ('TeamKyle','TeamRich','TeamJames', 'TeamKyleRich',1701,1700,1703,1704)
ORDER BY recording_id
LIMIT 100");
                        $query->bindParam(':DATE', $date, PDO::PARAM_STR);
                        $query->bindParam(':callTime', $callTime, PDO::PARAM_INT);
                        $query->bindParam(':USER', $user, PDO::PARAM_STR);

                    } elseif ($userFlag == 0 && $dispoFlag == 1 && $callTimeFlag == 0) { // SET DISPO ONLY
//echo "SET DISPO ONLY | User: $userFlag  | Dispo: $dispoFlag | Time: $callTimeFlag";
                        $query = $pdo->prepare("SELECT 
    lead_id, full_name, phone_number, location, status
FROM
    recording_log
        JOIN
    users ON users.user = recording_log.user
WHERE
        DATE(start_time) =:DATE AND YEAR(start_time) = '2020'
        AND user_group IN ('TeamKyle','TeamRich','TeamJames', 'TeamKyleRich',1701,1700,1703,1704) AND status=:DISPO
ORDER BY recording_id
LIMIT 100");
                        $query->bindParam(':DATE', $date, PDO::PARAM_STR);
                        $query->bindParam(':DISPO', $dispo, PDO::PARAM_STR);

                    } elseif ($userFlag == 0 && $dispoFlag == 1 && $callTimeFlag == 1) { // SET DISPO AND CALL TIME ONLY
//echo "SET DISPO AND CALL TIME ONLY | User: $userFlag  | Dispo: $dispoFlag | Time: $callTimeFlag";
                        $query = $pdo->prepare("SELECT 
    lead_id, full_name, phone_number, location, status
FROM
    recording_log
        JOIN
    users ON users.user = recording_log.user
WHERE
        DATE(start_time) =:DATE AND YEAR(start_time) = '2020' AND length_in_sec >=:callTime AND status=:DISPO
        AND user_group IN ('TeamKyle','TeamRich','TeamJames', 'TeamKyleRich',1701,1700,1703,1704)
ORDER BY recording_id
LIMIT 100");
                        $query->bindParam(':DATE', $date, PDO::PARAM_STR);
                        $query->bindParam(':callTime', $callTime, PDO::PARAM_INT);
                        $query->bindParam(':DISPO', $dispo, PDO::PARAM_STR);

                    } elseif ($userFlag == 0 && $dispoFlag == 0 && $callTimeFlag == 1) { // SET CALL TIME ONLY
//echo "SET CALL TIME ONLY | User: $userFlag  | Dispo: $dispoFlag | Time: $callTimeFlag";
                        $query = $pdo->prepare("SELECT 
    lead_id, full_name, phone_number, location, status
FROM
    recording_log
        JOIN
    users ON users.user = recording_log.user
WHERE
        DATE(start_time) =:DATE AND YEAR(start_time) = '2020' AND length_in_sec >=:callTime
        AND user_group IN ('TeamKyle','TeamRich','TeamJames', 'TeamKyleRich',1701,1700,1703,1704)
ORDER BY recording_id
LIMIT 100");
                        $query->bindParam(':DATE', $date, PDO::PARAM_STR);
                        $query->bindParam(':callTime', $callTime, PDO::PARAM_INT);

                    } elseif ($userFlag == 0 && $dispoFlag == 0 && $callTimeFlag == 0) { // NO FILTERS
//echo "NO FILTERS | User: $userFlag  | Dispo: $dispoFlag | Time: $callTimeFlag";
                        $query = $pdo->prepare("SELECT 
    lead_id, full_name, phone_number, location, status
FROM
    recording_log
        JOIN
    users ON users.user = recording_log.user
WHERE
        DATE(start_time) =:DATE AND YEAR(start_time) = '2020'
        AND user_group IN ('TeamKyle','TeamRich','TeamJames', 'TeamKyleRich',1701,1700,1703,1704)
ORDER BY recording_id
LIMIT 100");
                        $query->bindParam(':DATE', $date, PDO::PARAM_STR);

                    }

                    if (isset($dispo) && $dispo == '0') {
                        $displayDispo = 'All';
                    } else {
                        $displayDispo = $dispo;
                    }

                    if (isset($date)) {

                        $displayDate = new DateTime($date);
                        $displayDate = $displayDate->format('l jS \of F Y');
                    }

                    $query->execute();
                    if ($query->rowCount() > 0) {

                        ?>

                        <div class="alert alert-success" role="alert">
                            Recordings for <?php if (isset($date)) {
                                echo "Agent: <span class='alert-link'>$user</span> | Date: <span class='alert-link'>$displayDate</span> | Disposition: <span class='alert-link'>$displayDispo</span> | Call Length: <span class='alert-link'>$displayCallTime</span>";
                            } ?>
                        </div>

                        <table align="center" class="table">
                        <thead>
                        <tr>
                            <th>Row</th>
                            <th>Agent</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>

                        <?php

                        while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

                            ?>

                            <tr>
                                <td><?php if (isset($result['lead_id'])) {
                                        echo $result['lead_id'];
                                    } ?></td>
                                <td><?php if (isset($result['full_name'])) {
                                        echo $result['full_name'];
                                    } ?></td>
                                <td><?php if (isset($result['phone_number'])) {
                                        echo $result['phone_number'];
                                    } ?></td>
                                <td><?php if (isset($result['status'])) {
                                        echo $result['status'];
                                    } ?></td>
                                <td>
                                    <?php if ($result['location'] == 'Error') { ?>
                                        Error
                                    <?php } else { ?>
                                        <audio
                                                controls
                                                src="<?php if (isset($result['location'])) {
                                                    echo $result['location'];
                                                } ?>">
                                            Your browser does not support the
                                            <code>audio</code> element.
                                        </audio>
                                    <?php } ?>
                                </td>
                            </tr>

                            <?php

                        }
                    } else { ?>
                        <div class="alert alert-danger" role="alert">
                            No call recordings could be found for <?php if (isset($date)) {
                                echo "Agent: <span class='alert-link'>$user</span> | Date: <span class='alert-link'>$displayDate</span> | Disposition: <span class='alert-link'>$displayDispo</span>";
                            } ?>
                        </div>
                    <?php }

                    ?>
                    </table>

                    <?php

                }

                ?>

                <br>
            </div>
            </p>

        </div>
        <div class="card-footer">
            ADL
        </div>
    </div>
</div>

<div class="modal fade" id="loggedInModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Active Agents</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
<div class="row">
    <p>
                <?php
                $query = $pdo->prepare("SELECT 
    live_agents.status,
    full_name,
    TIMEDIFF(CURRENT_TIMESTAMP, last_call_time) AS Time,
    TIMEDIFF(CURRENT_TIMESTAMP, last_call_finish) AS callFinish,
    live_agents.lead_id,
    live_agents.uniqueid,
    live_agents.comments,
    sub_status,
    dead_epoch,
    dead_sec,
    event_time,
    phone_number,
    TIMEDIFF(CURRENT_TIMESTAMP, event_time) AS Time2
FROM
    live_agents
        JOIN
    users ON live_agents.user = users.user
        JOIN
    agent_log ON live_agents.agent_log_id = agent_log.agent_log_id
    LEFT JOIN outbound_log on outbound_log.uniqueid = live_agents.uniqueid
WHERE
    live_agents.campaign_id IN(1700,1300,1704,1701,1702)
ORDER BY live_agents.status , last_call_time
LIMIT 70");

                $dyn_table = '<div><table>';

                $query->execute();
                if ($query->rowCount() > 0) {
                    $i = 0;
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

                        if (isset($result['comments'])) {
                            $comments = $result['comments'];
                        }

                        if ($status == 'INCALL' || $status == 'MANUAL') {
                            $Time = $result['Time'];
                        } else {
                            $Time = $result['callFinish'];
                        }

                        if (isset($result['sub_status'])) {
                            $subStatus = $result['sub_status'];
                        }

                        if (isset($result['dead_epoch'])) {
                            $deadEpoch = $result['dead_epoch'];
                        } else {
                            $deadEpoch = null;
                        }


                        switch ($result['status']) {
                            case("READY"):
                                $class = 'status_READY';
                                $PAUSE_CODE_I_CLASS = "fa fa-clock";
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
                                $PAUSE_CODE_I_CLASS = "fa-phone";
                                if ($result['comments'] == 'INBOUND') {
                                    $result['status'] = 'TRANSFER';
                                    $class = 'status_trans';
                                } elseif ($Time < '00:00:98') {
                                    $result['status'] = 'INCALL';
                                    $class = 'status_INCALL10';
                                } elseif ($Time >= '00:00:99' && $Time < '00:02:99') {
                                    $result['status'] = 'INCALL';
                                    $class = 'status_INCALL1';
                                } elseif ($Time >= '00:02:99') {
                                    $result['status'] = 'INCALL';
                                    $class = 'status_INCALL5';
                                }
                                if (!is_null($deadEpoch)) {
                                    $status = 'DEAD';
                                    $class = 'status_DEAD';
                                } else {
                                    $status = 'INCALL';
                                }
                                break;
                            case("PAUSED"):
                                $PAUSE_CODE_I_CLASS = "fa-pause";
                                if ($lead_id > 0 && $comments = ' ') {
                                    $result['status'] = 'DISPO';
                                    $class = 'status_DISPO';
                                    $status = 'DISPO';
                                } elseif ($Time < '00:00:10') {
                                    $class = 'status_PAUSED1';
                                } elseif ($Time >= '00:00:10' && $Time < '00:04:99') {
                                    $class = 'status_PAUSED10';
                                } elseif ($Time > '00:04:99') {
                                    $class = 'status_PAUSED5';
                                } else {
                                    $class = 'status_PAUSED';
                                }
                                break;
                            case("QUEUE"):
                                $class = 'status_QUEUE';
                                $PAUSE_CODE_I_CLASS = "fa-clock";
                                break;
                            default:
                                $class = 'status_READY';
                                break;
                        }

                        if ($i % 5 == 0) {
                            $dyn_table .= '<tr><td class=' . $class . ' style="font-weight: bold; text-align:center">' . $result['full_name'] . '<br><i class="fa ' . $PAUSE_CODE_I_CLASS . '"></i> ' . $result['Time'] . '</td>';
                        } else {
                            $dyn_table .= '<td class=' . $class . ' style="font-weight: bold; text-align:center">' . $result['full_name'] . '<br><i class="fa ' . $PAUSE_CODE_I_CLASS . '"></i> ' . $result['Time'] . '</td>';
                        }
                        $i++;
                    }
                    $dyn_table .= '</tr></table></div>';
                }

                echo $dyn_table;

            ?>
    </p>
</div>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
<script type="text/javascript" src="/jquery/jquery-3.0.0.min.js"></script>
<script type="text/javascript" src="/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script>
    $(function () {
        $("#date").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true
        });
    });

    $('form#searchform').submit(function () {
        $(this).find(':input[type=submit]').prop('disabled', true);
    });

    $(window).on("load", function (e) {
        $('.loader').fadeOut();
    })
</script>
</body>
</html>
