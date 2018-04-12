<?php
    /**
     * Created by PhpStorm.
     * User: Patrick Zalewski
     * Date: 2018-04-11
     * Time: 9:32 AM
     */

    define("LOW_PEAK_RATE", 0.065);
    define("MID_PEAK_RATE", 0.095);
    define("HIGH_PEAK_RATE", 0.132);

    $dbhost = "localhost";
    $dbuser= "postgres";
    $dbpass = "Zalewski3!";
    $dbname = "smartmeters";
    $connection = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass");
    if (!$connection) {
        echo "Database Connection Failed";
    }

    $meter_id = 1;
    $point = 1;
    $queryHourlyUsage = "select usage, date_trunc('hour', start_time) as time
                        from meter_reads
                        where meter_id = $meter_id
                        and point = $point
                        order by time ";
    $result = pg_query($queryHourlyUsage);
    if (!$result)
        die ("Database query failed!");

    $array_index = 0;
    $highPeak_usage = array(); $midPeakUsage = array(); $lowPeakUsage = array();

    for ($month = 8; $month < 12; $month++) {
        $totalHighPeak = 0; $totalMidPeak = 0; $totalLowPeak = 0;
        $numberOfDays = cal_days_in_month(CAL_GREGORIAN, $month, 2017);
        for($day = 1; $day < $numberOfDays; $day++) {
            for($hour = 1; $hour <= 24; $hour++) {
                $row = pg_fetch_array($result);
                $time = date_parse($row["time"]);
                if ($time["hour"] >= 11 && $time["hour"] <= 17) { //Peak time
                    $totalHighPeak += $row["usage"] * HIGH_PEAK_RATE;
                } else if ($time["hour"] >= 18 || $time["hour"] <= 6) { //low-peak
                    $totalLowPeak += $row["usage"]* LOW_PEAK_RATE;
                } else { //mid-peak
                    $totalMidPeak += $row["usage"] * MID_PEAK_RATE;
                }
            }
        }
        array_push($highPeak_usage, round($totalHighPeak,2));
        array_push($midPeakUsage, round($totalMidPeak, 2));
        array_push($lowPeakUsage, round($totalLowPeak,2));

    }
    $energyUsageByPeak = array();
    array_push($energyUsageByPeak, $highPeak_usage);
    array_push($energyUsageByPeak, $midPeakUsage);
    array_push($energyUsageByPeak, $lowPeakUsage);

    echo json_encode($energyUsageByPeak);
?>