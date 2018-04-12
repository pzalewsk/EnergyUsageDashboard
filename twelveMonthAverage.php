<?php
    /**
     * Created by PhpStorm.
     * User: Patrick Zalewski
     * Date: 2018-01-27
     * Time: 11:18 AM
     */
    $dbhost = "localhost";
    $dbuser= "postgres";
    $dbpass = "Zalewski3!";
    $dbname = "smartmeters";
    $connection = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass");
    if (!$connection) {
        echo "Database Connection Failed";
    }

    $propertyId = $_GET['propertyId'];
    $twelveMonthData = array();

    $query_get_meter_points = "SELECT id
                               FROM meters
                               WHERE property_id = $propertyId
                               ORDER BY id";

    $result_get_meter_points = pg_query($query_get_meter_points);
    $number_of_meters = pg_num_rows($result_get_meter_points)/24;
    $meter_ids = array();
    for($i = 0; $i < $number_of_meters; $i++){
        while ($row = pg_fetch_array($result_get_meter_points)) {
           array_push($meter_ids, "'" . $row['id']."'");
        }
    }

    $commaList = implode(',', $meter_ids);

    $query_get_meter_points = "SELECT id
                               FROM meters
                               WHERE property_id = '1'
                               ORDER BY id";

    $result_get_meter_points = pg_query($query_get_meter_points);

    for ($i = 8; $i < 12; $i++) {
        $month = (string)$i;
        $startDate = "2017-" . $month . "-01";
        $endDate = "2017-" . $month . "-30";

        $query =
            "SELECT property_id, SUM(usage)
            FROM meter_reads
            JOIN meters ON meters.id = meter_reads.meter_id
            WHERE meters.property_id = 1
            AND start_time BETWEEN  '" . $startDate . "' AND '" . $endDate . "'
            GROUP BY property_id";

        $result = pg_query($query);
        $total_usage = 0;
        if (!$result)
            die ("Database query failed!");
        while ($row = pg_fetch_array($result)) {
            $total_usage += $row["sum"];
        }
        array_push($twelveMonthData,round($total_usage,2));
    }
    echo json_encode($twelveMonthData);
?>