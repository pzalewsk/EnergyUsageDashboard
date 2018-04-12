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

    //COMMENTED OUT SECTION WILL BE USED WHEN USING UP TO DATE DATA
    /**
    $current_date = date_parse(date("Y/m/d"));
    $month_to_date = array();
    $startDate = "2017-" . $current_date["month"] . "-01";
    $endDate = "2017-" . $current_date["month"] . "-".$current_date["day"];
     *
    $end_day = $endDate["day"];
    $end_month = $endDate["month"];
    $end_year = endDate["year"];
     **/
    $month_to_date = array();
    $start_date = "2017-09-01";
    $end_date = "2017-09-03";

    $end_month = 9;
    $end_day = 30;
    $end_year = 2017;
    $propertyId = $_GET['propertyId'];
    $query =  "SELECT property_id, start_time, SUM(usage)
               FROM meter_reads
               JOIN meters ON meters.id = meter_reads.meter_id
               WHERE meters.property_id = $propertyId
               AND start_time BETWEEN  '" . $start_date . "' AND '" . $end_date . "'
               GROUP BY property_id, start_time";

    $result = pg_query($query);
    if (!$result)
        die ("Database query failed!");

    for($i = 0; $i < $end_day; $i++){
        $current_day_sum = 0;
        for($j = 0; $j < 24; $j++){
            $row = pg_fetch_array($result);
            $current_day_sum += $row["sum"];
        }
        array_push($month_to_date, round($current_day_sum, 2));
    }

    echo json_encode($month_to_date);
?>