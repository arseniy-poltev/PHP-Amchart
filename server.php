<?php

require_once './DB/Mysql.php';
$host = "quantalysdb.cyjgvgpclt6h.eu-west-3.rds.amazonaws.com:3306";
$user = "bellait";
$password = "SAL423o4ok23DFf";
$database = "quant";

if (isset($_POST['name'])) {
    $db = new Mysql($host, $user, $password, $database);

    $res = $db->where('names', $_POST['name'])->get('data');

    if ($res) {
        $result['state'] = 'success';
        $result['data'] = $res;
    } else {
        $result['state'] = 'failed';
    }
    echo json_encode($result);
}

if (isset($_POST['url'])) {
    $db = new Mysql($host, $user, $password, $database);
    $url_val = "https://www.quantalys.com/Fonds/" . $_POST['url'];
    $res = $db->where('url', $url_val)->get('data');

    if ($res) {
        $result['state'] = 'success';
        $result['data'] = $res;
    } else {
        $result['state'] = 'failed';
    }
    echo json_encode($result);
}

?>