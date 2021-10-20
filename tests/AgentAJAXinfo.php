<?php

define("BASE_URL", (filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS)));

require_once(BASE_URL . '/includes/CONNEX_PDO_CON.php');

$query = $pdo->prepare("SELECT full_name as user,
    user_id,
       u.user_group,
       count(*) as calls,
       COUNT(IF(status = 'A', 1, NULL)) AS 'A',
       COUNT(IF(status = 'DNC', 1, NULL)) AS 'DNC',
       COUNT(IF(status = 'CALLBK', 1, NULL)) AS 'CALLBK',
       COUNT(IF(status = 'DC', 1, NULL)) AS 'DC',
       COUNT(IF(status = 'DEC', 1, NULL)) AS 'DEC',
       COUNT(IF(status = 'N', 1, NULL)) AS 'N',
       COUNT(IF(status = 'NI', 1, NULL)) AS 'NI',
       COUNT(IF(status = 'NP', 1, NULL)) AS 'NP',
       COUNT(IF(status = 'SALE', 1, NULL)) AS 'SALE',
       COUNT(IF(status = 'WN', 1, NULL)) AS 'WN',
       COUNT(IF(status = 'XFER', 1, NULL)) AS 'XFER',
       COUNT(IF(status = 'BSY', 1, NULL)) AS 'BSY',
       COUNT(IF(status = 'ADEC', 1, NULL)) AS 'ADEC',
       COUNT(IF(status = 'ANI', 1, NULL)) AS 'ANI',
       COUNT(IF(status = 'AQNB', 1, NULL)) AS 'AQNB',
       COUNT(IF(status = 'ANC', 1, NULL)) AS 'ANC',
       COUNT(IF(status = 'CS', 1, NULL)) AS 'CS',
       COUNT(IF(status = 'CE', 1, NULL)) AS 'CE',
       COUNT(IF(status = 'WC', 1, NULL)) AS 'WC',
       COUNT(IF(status = 'COMP', 1, NULL)) AS 'COMP',
       COUNT(IF(status = 'TWWI', 1, NULL)) AS 'TWWI',
       COUNT(IF(status = 'SLE', 1, NULL)) AS 'SLE',
       COUNT(IF(status = 'UPS', 1, NULL)) AS 'UPS',
       COUNT(IF(status = 'RUPS', 1, NULL)) AS 'RUPS',
       COUNT(IF(status = 'PSS', 1, NULL)) AS 'PSS',
       COUNT(IF(status = 'REINS', 1, NULL)) AS 'REINS',
       COUNT(IF(status = 'REDO', 1, NULL)) AS 'REDO',
       COUNT(IF(status = 'AHU', 1, NULL)) AS 'AHU',
       COUNT(IF(status = 'ACB', 1, NULL)) AS 'ACB',
       COUNT(IF(status = 'Wi', 1, NULL)) AS 'Wi',
       COUNT(IF(status = 'ADUW', 1, NULL)) AS 'ADUW',
       COUNT(IF(status = 'Pre', 1, NULL)) AS 'Pre',
       AVG(talk_sec) as avgTalkSec,
       AVG(dead_sec) as avgDead,
       AVG(wait_sec) as avgWaitSec,
       AVG(dispo_sec) as avgDispoSec
FROM agent_log JOIN users u on agent_log.user = u.user WHERE event_time >= CURDATE() AND status IS NOT NULL GROUP BY user");
$query->execute();
if ($query->rowCount() > 0) {

    $array = [
    ];


    $i = 0;
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    $checkArray = ['user', 'avgTalkSec', 'avgDead', 'avgWaitSec', 'avgDispoSec', 'calls', 'user_id', 'user_group'];

    foreach ($results as $rows):

        foreach ($rows as $key => $value):

            if (in_array($key, $checkArray)) {
                $array[$i][$key] = $value;
            } else {
                $array[$i]['DispoData'][$key] = $value;
            }


        endforeach;
        $i++;
    endforeach;

    # var_dump($array);

    json_encode($array);
    $data['data'] = $array;
    echo json_encode($data);

}
