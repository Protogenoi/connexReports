<?php
define("BASE_URL", (filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS)));

require_once(BASE_URL . '/includes/CONNEX_PDO_CON.php');

$totalSales = 0;
$totalXfers = 0;

$query = $pdo->prepare("SELECT list_id, SUM(talk_sec), status, count(*) AS statusCount FROM agent_log WHERE list_id=201911265 GROUP BY list_id, status");
$query->execute();
if ($query->rowCount() > 0) {

?>

    <div class="alert alert-success" role="alert">
        <span class='alert-link'>Agent performance for all Teams | Last refresh: <?php echo $Today_TIME = date("h:i:s"); ?></span>

    </div>

<table class="table table-sm" id="cacReport">
    <thead>
    <tr>
        <th>Campaign</th>
        <th>List ID</th>
        <th>Status</th>
        <th>Dialled</th>
        <th>Xfers</th>
        <th>Sales</th>
    </tr>
    </thead>

    <?php

    $totalDialled = 0;

    while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

        $listID = $result['list_id'];
        $status = $result['status'];

        switch ($status):
            case 'SALE';
                $totalSales = $result['statusCount'];
                break;
            case 'XFER';
                $totalXfers = $result['statusCount'];
                break;
            default;
                $totalSales = 0;
                $totalXfers = 0;
        endswitch;

        $totalDialled = $result['statusCount'];


            ?>

            <tr>
                <td>1700</td>
                <td><?php if (isset($listID)) {
                        echo $listID;
                    } ?></td>
                <td><?php if (isset($status)) {
                        echo $status;
                    } else {
                    echo 'NULL';
                    }?></td>
                <td><?php if (isset($totalDialled)) {
                        echo $totalDialled;
                    } ?></td>
                <td><?php if (isset($totalXfers)) {
                        echo $totalXfers;
                    } else {
                    echo 0;
                    } ?></td>
                <td><?php if (isset($totalSales)) {
                        echo $totalSales;
                    } else {
                        echo 0;
                    } ?></td>
            </tr>

            <?php


    }
    } else { ?>
    <div class="alert alert-danger" role="alert"><span class='alert-link'>No Stats found</span>
        <?php } ?>
    </div>

</table>

