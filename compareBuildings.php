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

    class month_totals{
        public $property_totals = array();
    }

    $propertyId = $_GET['propertyId'];
    $twelveMonthData = array();

    $queryGetPropertySize = "SELECT COUNT(property_id)
                             FROM meters
                             WHERE property_id = $propertyId";
    $pgQueryGetPropertySize = pg_query($queryGetPropertySize);
    $rowGetPropertySize = pg_fetch_array($pgQueryGetPropertySize);
    $propertySize = $rowGetPropertySize["count"];

    $queryGetSimilarSizeBuilding = "SELECT property_id
                                    FROM meters
                                    GROUP BY property_id
                                    HAVING COUNT(property_id) = $propertySize
                                    ORDER BY property_id
                                    LIMIT 3";
    $pgQueryComparableBuildings = pg_query($queryGetSimilarSizeBuilding);
    $comparableBuildings = array();
    while ($row = pg_fetch_array($pgQueryComparableBuildings)) {
        array_push($comparableBuildings, $row["property_id"]);
    }

    for ($i = 8; $i < 12; $i++) {
        $month = (string)$i;
        //NEED TO CHANGE YEAR FIELD TO BE RESPONSIVE TO CURRENT YEAR.
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $i, 2017);
        $startDate = "2017-" . $month . "-01";
        $endDate = "2017-" . $month . "-" . $daysInMonth;
        for($j = 0; $j < 3; $j++){
            $query =  "SELECT property_id, SUM(usage)
                       FROM meter_reads
                       JOIN meters ON meters.id = meter_reads.meter_id
                       WHERE meters.property_id IN ($comparableBuildings[0], $comparableBuildings[1], $comparableBuildings[2])
                       AND start_time BETWEEN  '" . $startDate . "' AND '" . $endDate . "'
                       GROUP BY property_id";
            $result = pg_query($query);
            $four_month_data = new month_totals();

            if (!$result)
                die ("Database query failed!");
            while ($row = pg_fetch_array($result)) {
                array_push($four_month_data->property_totals, $row["sum"]);
            }
        }
        array_push($twelveMonthData, $four_month_data);
    }
    echo json_encode($twelveMonthData);
?>