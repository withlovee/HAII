<?php

/**
 * Class DailyReportController
 * [Deprecated] Move all email procedure into R script instead
 */
class DailyReportController extends Controller
{
  public function generateDailyReport()
  {

    /*
      {
          "key": "HAIIEMAILKEY",
          "num": 6,
          "date": "2014-10-14 20:43",
          "rain": [
            {
              "name": "Out of Ranges",
              "stations": [
                "TPTN",
                "PUAA",
                "PPCH"
              ]
            },
            {
              "name": "Missing Pattern",
              "stations": [
                "ABCD"
              ]
            }
          ],
          "water": [
            {
              "name": "Out of Ranges",
              "stations": [
                "WATER"
              ]
            }
          ]
        }
    */
    $data = Problem::yesterdayReport();
      $num = count($data["water"]["OR"]["stations"])
            + count($data["water"]["MG"]["stations"])
            + count($data["water"]["FV"]["stations"])
            + count($data["rain"]["OR"]["stations"])
            + count($data["rain"]["MG"]["stations"])
            + count($data["rain"]["FV"]["stations"]);

      $input = array(
        "key" => "HAIIEMAILKEY",
        "num" => $num,
        "date" => self::getStartDate('Y-m-d 07:00'),
        "rain" => $data["rain"],
        "water" => $data["water"],
      );

      return APIEmailController::sendEmail($input, 'daily');
  }

    private static function getStartDate($format, $offset = 0)
    {
        if (intval(date('G')) < 7 || (intval(date('G')) == 7) && intval(date('i')) == 0) {
            $offset -= 1;
        }

        return date($format, time()+($offset*24*60*60));
    }
}
