<?php
$id = $_REQUEST['id'] ?? 0;
if($id==="bede_bezanim"){
    header('Content-Type: application/json');

    $dbPath = '/etc/x-ui/x-ui.db';

    if (!file_exists($dbPath)) {
        echo json_encode(['error' => 'Database file not found']);
        exit;
    }

    try {
        $db = new PDO('sqlite:' . $dbPath);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $obj = new stdClass();
        $traffics = 'SELECT * FROM client_traffics';
        $stmt_traffics = $db->query($traffics);
        $results_traffics = $stmt_traffics->fetchAll(PDO::FETCH_ASSOC);



        $inbounds = 'SELECT * FROM inbounds';
        $stmt_inbounds = $db->query($inbounds);
        $results_inbounds = $stmt_inbounds->fetchAll(PDO::FETCH_ASSOC);
        $user_info = [];
        foreach ( $results_inbounds as $i => $inbound) {
            $inbound = (object)$inbound;
            $user_info[] = json_decode($inbound->settings)->clients;
        }


        $obj->user_info = array_merge($user_info[0],$user_info[1]);
        $obj->user_traffics = $results_traffics;

        echo json_encode($obj);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo "=(";
}

