<?php

/**
 * Class APIEmailController
 * Sample POST Input:
 * {
 *     "key": "HAIIEMAILKEY",
 *     "num": 6,
 *     "date": "2014-10-14 20:43",
 *     "rain": [
 *         {
 *             "name": "Out of Ranges",
 *             "stations": [
 *                 "TPTN",
 *                 "PUAA",
 *                 "PPCH"
 *             ]
 *         },
 *         {
 *             "name": "Missing Pattern",
 *             "stations": [
 *                 "ABCD"
 *             ]
 *         }
 *     ],
 *     "water": [
 *         {
 *             "name": "Out of Ranges",
 *             "stations": [
 *                 "WATER"
 *             ]
 *         }
 *     ]
 * }
 */
class APIEmailController extends BaseController
{
    /**
     * Retrive POST input from R
     * @param string    $type   email type (instantly, daily, monthly)
     * @return mixed
     */
    public function sendAlert($type)
    {
        $data = Input::all();

        return APIEmailController::sendEmail($data, $type);
    }


    /**
     * Send email using MailGun
     * @param array     $data   email data in json format
     * @param string    $type   email type (instantly, daily, monthly)
     * @return mixed            response
     */
    public static function sendEmail($data, $type)
    {
        $emailTypes = ['instantly', 'daily', 'monthly'];

        if (!in_array($type, $emailTypes)) {
            return Response::json(['error' => 'incorrect type'], 400);
        }
        if ($data['key'] != 'HAIIEMAILKEY') {
            return Response::json(['error' => 'incorrect key'], 400);
        }

        # Get list of user who accept certain report type
        $users = User::where('report_'.$type, '=', true)->get()->toArray();

        $tempalte = "";
        $subject = "";

        if ($type == 'instantly') {
            $template = 'emails.instantly';
            $subject = '[QC.HAII] '.$data['num'].' Problem(s) Detected at '.$data['date'];
        } elseif ($type == 'daily') {
            $template = 'emails.report';
            $subject = '[QC.HAII] Daily Report | '.$data['startdate'].' - '.$data['enddate'];
            $data['reportName'] = 'Daily Report';
        } elseif ($type == 'monthly') {
            $template = 'emails.report';
            $subject = '[QC.HAII] Monthly Report | '.$data['startdate'].' - '.$data['enddate'];
            $data['reportName'] = 'Monthly Report';
        }

        # send email to each users
        foreach ($users as $user) {
            Mail::send($template, $data, function ($message) use ($data, $user, $subject) {
                $message->to($user['email'], $user['username']);
                $message->subject($subject);
            });
        }

        return Response::json(['success' => true]);
    }
}
