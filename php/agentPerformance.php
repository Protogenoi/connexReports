<?php
define("BASE_URL", (filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS)));

require_once(BASE_URL . '/includes/CONNEX_PDO_CON.php');
//require_once(BASE_URL . '/includes/CONNEX_PDO_CON_NEW.php');

$totalSales = 0;
$totalXfers = 0;

$aAAgentSales = 0;
$cTAgentSales = 0;
$jDAgentSales = 0;
$cWAgentSales = 0;
$aMAgentSales = 0;
$sAgentSales = 0;
$lmAgentSales = 0;
$bPAgentSales = 0;
$tJAgentSales = 0;
$fEAgentSales = 0;
$sJAgentSales = 0;
$rDAgentSales = 0;
$aRAgentSales = 0;
$sHAgentSales = 0;
$aWAgentSales = 0;
$lAAgentSales = 0;
$tWAgentSales = 0;
$rJAgentSales = 0;
$gMAgentSales = 0;
$cBAgentSales = 0;
$rOAgentSales = 0;
$joeDAgentSales = 0;
$cSAgentSales = 0;
$dEAgentSales = 0;

$nGAgentSales = 0;
$dEdAgentSales = 0;
$rTAgentSales = 0;
$sLAgentSales = 0;
$sJaAgentSales = 0;
$cDAgentSales = 0;

$lRAgentSales = 0;
$sKAgentSales = 0;
$mRAgentSales = 0;

$jRAgentSales = 0;
$nHAgentSales = 0;
$cGAgentSales = 0;
$eMAgentSales = 0;
$nJAgentSales = 0;
$dKAgentSales = 0;
$sTAgentSales = 0;
$shHAgentSales = 0;
$raJAgentSales = 0;
$aLAgentSales = 0;


    $query = $pdo->prepare("SELECT 
    full_name, COUNT(*) AS totalSales
FROM
    agent_log
        LEFT JOIN
    users ON users.user = agent_log.user
WHERE
    status = 'XFER'
        AND lead_id IN (SELECT 
            lead_id
        FROM
            agent_log
        WHERE
            event_time >= CURDATE()
                AND status = 'SALE')
        AND agent_log.user_group IN ('TeamRich' , 'TeamKyle', 'TeamJames','1700','1700_1300','NEW_STARTERS', 'TeamKyleRich', 'RENEW_AND_CLOSERS') AND event_time >= CURDATE()
GROUP BY agent_log.user");

    $queryGetXfers = $pdo->prepare("SELECT 
    SUM(IF(status = 'XFER', 1, 0)) AS XFERS,
    SUM(IF(status = 'SALE', 1, 0)) AS SALES,
    COUNT(*) AS callCount,
    agent_log.user,
    agent_log.user_group,
    SUM(talk_sec) AS talkSec,
    full_name
FROM
    agent_log
        LEFT JOIN
    users ON users.user = agent_log.user
WHERE
    event_time >= CURDATE()
        AND lead_id != ''
GROUP BY agent_log.user
ORDER BY full_name , XFERS");

    $query->execute();
if ($query->rowCount() > 0) {
    while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

        switch ($result['full_name']):
            case 'Adam-Arrigan';
                $aAAgentSales = $result['totalSales'];
                break;
            case 'Chris-Thomas';
                $cTAgentSales = $result['totalSales'];
                break;
                case 'Jennifer-Doran';
                $jDAgentSales = $result['totalSales'];
                break;
            case 'Connor-Williams';
                $cWAgentSales = $result['totalSales'];
                break;
            case 'Aaron-Mckay';
                $aMAgentSales = $result['totalSales'];
                break;
            case 'Stavros';
                $sAgentSales = $result['totalSales'];
                break;
            case 'Lee McDonaugh';
                $lmAgentSales = $result['totalSales'];
                break;
            case 'Brandon Preece';
                $bPAgentSales = $result['totalSales'];
                break;
            case 'Tom-Jones';
                $tJAgentSales = $result['totalSales'];
                break;
            case 'Ffion-Edwards';
                $fEAgentSales = $result['totalSales'];
                break;
            case 'Sophie-Jones';
                $sJAgentSales = $result['totalSales'];
                break;
            case 'Ricky';
                $rDAgentSales = $result['totalSales'];
                break;
            case 'Abbie Rowden';
                $aRAgentSales = $result['totalSales'];
                break;
            case 'Stephen Howard';
                $sHAgentSales = $result['totalSales'];
                break;
            case 'Ashleigh Woodgate';
                $aWAgentSales = $result['totalSales'];
                break;
            case 'Lauren-Ace';
                $lAAgentSales = $result['totalSales'];
                break;
            case 'Tomas-Williams';
                $tWAgentSales = $result['totalSales'];
                break;
            case 'Rachel Jones';
                $rJAgentSales = $result['totalSales'];
                break;
            case 'George-Matthews';
                $gMAgentSales = $result['totalSales'];
                break;
                case 'Chloe Bradford';
                $cBAgentSales = $result['totalSales'];
                break;
                case 'Ryan-Oliver';
                $rOAgentSales = $result['totalSales'];
                break;
            case 'Joe-Davies';
                $joeDAgentSales = $result['totalSales'];
                break;
                case 'Christian-Sayers';
                $cSAgentSales = $result['totalSales'];
                break;
            case 'Daniel-Edwards';
                $dEAgentSales = $result['totalSales'];
                break;
                case 'Chloe-Dando';
                $cDAgentSales = $result['totalSales'];
                break;
            case 'Sam-Jarvis';
                $sJaAgentSales = $result['totalSales'];
                break;
                case 'Stephan-Leyson';
                $sLAgentSales = $result['totalSales'];
                break;
                case 'Ryan Tidball';
                $rTAgentSales = $result['totalSales'];
                break;
                case 'Declan-Edwards';
                $dEdAgentSales = $result['totalSales'];
                break;
                case 'Natasha Greene';
                $nGAgentSales = $result['totalSales'];
                break;
                case 'Luke Ratner';
                $lRAgentSales = $result['totalSales'];
                break;
                case 'Sharne Knight';
                $sKAgentSales = $result['totalSales'];
                break;
                case 'Marc Richards';
                $mRAgentSales = $result['totalSales'];
                break;
            case 'Abi Lesuisse';
                $aLAgentSales = $result['totalSales'];
                break;
            case 'Neil Herbert';
                $nHAgentSales = $result['totalSales'];
                break;
            case 'Casey Grey';
                $cGAgentSales = $result['totalSales'];
                break;
            case 'Ebony Mia';
                $eMAgentSales = $result['totalSales'];
                break;
            case 'Nicola James';
                $nJAgentSales = $result['totalSales'];
                break;
            case 'Daniel Kirkhouse';
                $dKAgentSales = $result['totalSales'];
                break;
            case 'Sara Tristram';
                $sTAgentSales = $result['totalSales'];
                break;
            case 'Shannon Hanney';
                $shHAgentSales = $result['totalSales'];
                break;
            case 'Rachel James';
                $raJAgentSales = $result['totalSales'];
                break;
            case 'Jeff Rees';
                $jRAgentSales = $result['totalSales'];
                break;
            default;
                $agentSales = 0;
        endswitch;


    }
}

$queryGetXfers->execute();
if ($queryGetXfers->rowCount() > 0) {

?>

    <div class="alert alert-success" role="alert">
        <span class='alert-link'>Agent performance for all Teams | Last refresh: <?php echo $Today_TIME = date("h:i:s"); ?></span>

    </div>

<table class="table table-sm" id="agentTable">
    <thead>
    <tr>
        <th>Agent</th>
        <th>Team</th>
        <th>Avg Talk (mins)</th>
        <th>Xfers</th>
        <th>Sales</th>
        <th>CR</th>
    </tr>
    </thead>

    <?php

    while ($result = $queryGetXfers->fetch(PDO::FETCH_ASSOC)) {

        $displayTeam = $result['user_group'];

        switch ($result['user_group']):
            case 'TeamKyle':
                $displayTeam = 'Kyle';
                break;
            case 'TeamRich':
                $displayTeam = 'Rich';
                break;
            case 'TeamJames':
                $displayTeam = 'James';
                break;
            default:
                $displayTeam = $result['user_group'];
        endswitch;

        switch ($result['full_name']):
            case 'Adam-Arrigan';
                $agentSales = $aAAgentSales;
                break;
            case 'Connor-Williams';
                $agentSales = $cWAgentSales;
                break;
            case 'Aaron-Mckay';
                $agentSales = $aMAgentSales;
                break;
            case 'Stavros';
                $agentSales = $sAgentSales;
                break;
            case 'Lee McDonaugh';
                $agentSales = $lmAgentSales;
                break;
            case 'Brandon Preece';
                $agentSales = $bPAgentSales;
                break;
            case 'Tom-Jones';
                $agentSales = $tJAgentSales;
                break;
            case 'Ffion-Edwards';
                $agentSales = $fEAgentSales;
                break;
            case 'Sophie-Jones';
                $agentSales = $sJAgentSales;
                break;
            case 'Ricky';
                $agentSales = $rDAgentSales;
                break;
            case 'Abbie Rowden';
                $agentSales = $aRAgentSales;
                break;
            case 'Stephen Howard';
                $agentSales = $sHAgentSales;
                break;
            case 'Ashleigh Woodgate';
                $agentSales = $aWAgentSales;
                break;
            case 'Lauren-Ace';
                $agentSales = $lAAgentSales;
                break;
            case 'Tomas-Williams';
                $agentSales = $tWAgentSales;
                break;
            case 'Rachel Jones';
                $agentSales = $rJAgentSales;
                break;
            case 'George-Matthews';
                $agentSales = $gMAgentSales;
                break;
                case 'Chris-Thomas';
                $agentSales = $cTAgentSales;
                break;
                case 'Jennifer-Doran';
                $agentSales = $jDAgentSales;
                break;
                case 'Chloe Bradford';
                $agentSales = $cBAgentSales;
                break;
                case 'Ryan-Oliver';
                $agentSales = $rOAgentSales;
                break;
                case 'Christian-Sayers';
                $agentSales = $cSAgentSales;
                break;
                case 'Daniel-Edwards';
                $agentSales = $dEAgentSales;
                break;
                case 'Joe-Davies';
                $agentSales = $joeDAgentSales;
                break;
                case 'Chloe-Dando';
                $agentSales = $cDAgentSales;
                break;
                case 'Sam-Jarvis';
                $agentSales = $sJaAgentSales;
                break;
                case 'Stephan-Leyson';
                $agentSales = $sLAgentSales;
                break;
                case 'Ryan Tidball';
                $agentSales = $rTAgentSales;
                break;
                case 'Declan-Edwards';
                $agentSales = $dEdAgentSales;
                break;
                case 'Natasha Greene';
                $agentSales = $nGAgentSales;
                break;
                case 'Luke Ratner';
                $agentSales = $lRAgentSales;
                break;
                case 'Sharne Knight';
                $agentSales = $sKAgentSales;
                break;
                case 'Marc Richards';
                $agentSales = $mRAgentSales;
                break;
                case 'Abi Lesuisse';
                $agentSales = $aLAgentSales;
                break;
                case 'Neil Herbert';
                $agentSales = $nHAgentSales;
                break;
                case 'Casey Grey';
                $agentSales = $cGAgentSales;
                break;
                case 'Ebony Mia';
                $agentSales = $eMAgentSales;
                break;
                case 'Nicola James';
                $agentSales = $nJAgentSales;
                break;
                case 'Daniel Kirkhouse';
                $agentSales = $dKAgentSales;
                break;
                case 'Sara Tristram';
                $agentSales = $sTAgentSales;
                break;
                case 'Shannon Hanney';
                $agentSales = $shHAgentSales;
                break;
                case 'Rachel James';
                $agentSales = $raJAgentSales;
                break;
                case 'Jeff Ress';
                $agentSales = $jRAgentSales;
                break;
            default;
                $agentSales = 0;
        endswitch;

        $totalSales += $result['SALES'];
        $totalXfers += $result['XFERS'];


        $callCount = $result['callCount'];
        $calCallTime = $result['talkSec'] / $callCount;

        $displayTime = gmdate("i.s", $calCallTime);

        if($agentSales > 0) {
            $conversionRate = $result['XFERS'] / $agentSales;
            $sectionFormattedRate = number_format($conversionRate, 1);
        } else {
            $sectionFormattedRate = 0;
        }

        $agentName = str_replace('-', ' ', $result['full_name']);

        $teamArray = ['Rich','Kyle','James',1700, 'NEW_STARTERS','TeamKyleRich', 'RENEW_AND_CLOSERS'];

        if(in_array($displayTeam, $teamArray)) {

            ?>

            <tr>
                <td><?php if (isset($result['full_name'])) {
                        echo "<a href='//renew-life.adl-crm.uk/addon/Trackers/Export/agentStats.php?EXECUTE=3&agent=$agentName'>$agentName</a>";
                    } ?></td>
                <td><?php if (isset($displayTeam)) {
                        echo $displayTeam;
                    } ?></td>
                <td><?php if (isset($displayTime)) {
                        echo $displayTime;
                    } ?></td>
                <td><?php if (isset($result['XFERS'])) {
                        echo $result['XFERS'];
                    } ?></td>
                <td><?php if (isset($agentSales)) {
                        echo $agentSales;
                    } else {
                        echo 0;
                    } ?></td>
                <td><?php if (isset($sectionFormattedRate)) {
                        echo $sectionFormattedRate;
                    } ?></td>
            </tr>

            <?php

        }

    }
    } else { ?>
    <div class="alert alert-danger" role="alert"><span class='alert-link'>No Stats found</span>
        <?php } ?>
    </div>

</table>

<?php

if($totalSales > 0) {
    $conversionRate = $totalXfers / $totalSales;
    $sectionFormattedRate = number_format($conversionRate, 1);
} else {
    $sectionFormattedRate = 0;
}

?>

<div class="alert alert-success" role="alert">
    Xfers: <span class='alert-link'> <?php echo $totalXfers; ?></span> | Sales: <span class='alert-link'> <?php echo $totalSales; ?> </span> | CR: <span class='alert-link'> <?php echo $sectionFormattedRate; ?></span>
</div>
