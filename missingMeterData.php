<?php
/**
 * Created by PhpStorm.
 * User: Patrick Zalewski
 * Date: 2018-01-27
 * Time: 11:19 AM
 */


    $dbhost = "localhost";
    $dbuser= "postgres";
    $dbpass = "Zalewski3!";
    $dbname = "smartmeters";
    $connection = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass");
    if (!$connection) {
        echo "Database Connection Failed";
    }

    $clientId = $_GET['clientId'];
    $query='SELECT id, created_at, property_id, serial 
            FROM meters
            ORDER BY property_id
            LIMIT 10';

    $result = pg_query($query);
    if (!$result) {
        die ("Database query failed!");
    }

    $unreadMeters = array();
    while ($row = pg_fetch_array($result)) {
        $meter = array(
            "MeterId" => $row["id"],
            "DateCreated" => $row["created_at"],
            "PropertyId" => $row["property_id"]
        );
        $unreadMeters[] = $meter;
    }

    echo json_encode($unreadMeters);
?>