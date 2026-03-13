<?php

function getAllSlaveDatabases() {

    // 👉 यहाँ अपने MASTER DB का connection डालो
    $host = "localhost";
    $user = "root";
    $pass = "Mishra@@1";
    $db   = "datalaysschool";

    $con = new mysqli($host, $user, $pass, $db);

    if ($con->connect_error) {
        die("Master DB connection failed");
    }

    $data = [];

    $sql = "SELECT db_name, db_user, db_pass , root_path , base_url 
            FROM schools_master 
            WHERE status = 1";

    $res = $con->query($sql);

    while ($row = $res->fetch_assoc()) {
        $data[] = [
            'db'   => $row['db_name'],
            'user' => $row['db_user'],
            'pass' => $row['db_pass'],
            'root' => $row['root_path'],
            'base' => $row['base_url'],
        ];
    }

    return $data;
}
?>