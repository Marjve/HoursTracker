<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TimeLogController extends Controller
{
    /**
     * get last check in or checkout by user
     *
     * @param Request $request
     * @return \Illuminate\Support\Collection
     */
    public function lastLogByUser(Request $request)
    {
        $user_id  = isset($request->user_id)  ? $request->user_id  : '';
        if(!$user_id)
            return false;

        $lastLog = DB::table('timeLog')
            ->where('user_id','=', $user_id)
            ->orderByDesc('created_at')
            ->limit(1)
            ->get();

        return $lastLog;
    }

    /**
     * add check in into db
     * update slack channels
     *
     * @param Request $request
     * @return int
     */
    public function addCheckIn(Request $request)
    {
        $curr_date = date("Y-m-d H:i:s");
        $user_id  = isset($request->user_id)  ? $request->user_id  : '';

        $team = $this->getDepartmentName($user_id);


        $id = DB::table('timeLog')->insertGetId(
            ['user_id' => $user_id, 'check_in' => $curr_date, 'created_at' => $curr_date]
        );
        return $id;
    }


    /**
     * update time log, add check out
     *
     * @param Request $request
     * @return bool|int
     */
    public function addCheckOut(Request $request)
    {
        $id  = isset($request->id)  ? $request->id  : 0;
        $user_id  = isset($request->user_id)  ? $request->user_id  : 0;
        if(!$id || !$user_id)
            return false;
        $check_in = $this->getLastCheckIn($id);
        if($check_in)
        {
            $curr_date = date("Y-m-d H:i:s");
            $date1 = strtotime($check_in);
            $date2 = strtotime($curr_date);
            $diff = abs($date1 - $date2);
            $hours = floor($diff/60/60);
            if($hours < 10)
                $hours = '0' . $hours;
            $minutes = floor(($diff - $hours*60*60)/ 60);
            if($minutes < 10)
                $minutes = '0' . $minutes;
            $seconds = floor($diff - $hours*60*60 - $minutes*60);
            if($seconds < 10)
                $seconds = '0' . $seconds;
            $time = $hours . ":" . $minutes . ":" . $seconds;

            $team = $this->getDepartmentName($user_id);


            $response = DB::table('timeLog')
                ->where('id', $id)
                ->where('user_id', $user_id)
                ->update(['check_out' => $curr_date, 'hours' => $time, 'updated_at' => $curr_date]);

            if($response)
                return $response;
            else
                return false;
        }
        else
            return false;
    }

    /**
     * get last check in by user
     *
     * @param $id
     * @return bool
     */
    public function getLastCheckIn($id)
    {
        $data = DB::table('timeLog')
            ->where('id', $id)
            ->limit(1)
            ->get();

        if($data)
            return $data[0]->check_in;
        else
            return false;
    }

    /**
     * add relevant info into slack channels
     * tropical or zeg
     *
     * @param $message
     * @param string $team
     * @param string $room
     * @param $link
     * @return bool|string
     */
    public function slack($message, $link, $team = "CMS", $room = "virtualoffice")
    {
        if ($team == "CMS") {
            $icon = ":z:";
        } elseif ($team == "HMM") {
            $icon = ":clapper:";
        } else {
            $icon = ":office:";
        }
        $data = "payload=" . json_encode(array(
                "username" => "Virtual Office Assistant - " . $team,
                "channel" => "#{$room}",
                "text" => $message,
                "icon_emoji" => $icon,
            ));

         $ch = curl_init($link);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * get department name by user id
     *
     * @param Request $request
     * @return mixed
     */
    public function getDepartmentName($user_id)
    {
        $result = DB::table('users')
            ->join('departments', 'users.department_id', '=','departments.id')
            ->where('users.id', '=', $user_id)
            ->select('departments.name')
            ->first();

        return $result->name ?? false;
    }

    /**
     * get active users
     *
     * @return \Illuminate\Support\Collection
     */
    public function getActiveUsers()
    {
        $result = DB::table('timeLog')
            ->join('users', 'users.id', '=','timeLog.user_id')
            ->whereRaw('timeLog.check_in is not null and timeLog.check_out is null and users.username is not null')
            ->select('users.id', 'users.username', 'users.name', 'timeLog.check_in')
            ->orderByDesc('timeLog.created_at')
            ->limit(10)
            ->get();

        return $result;
    }
}
