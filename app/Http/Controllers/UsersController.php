<?php

namespace App\Http\Controllers;

use App\Department;
use App\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UsersController extends Controller
{
    /**
     * get all logged time
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllLoggedTime(Request $request)
    {
        $per_page = isset($request->per_page) ? $request->per_page : 10;
        $limit    = isset($request->limit) ? $request->limit : 200;
        $sort_field = $request->sort_field ?? 'timeLog.created_at';
        $sort_order = (!empty($request->sort_order)) ? ($request->sort_order == 'descending') ? 'desc' : 'asc' : '';
        $id = ( $request->id != 0) ? $request->id : 0;
        $search    = ($request->search !== 0)  ? $request->search : 0;
        $date_from = ($request->date_from!=0) ? date('Y-m-d', strtotime($request->date_from)) : 0;
        $date_to   = ($request->date_to!=0)   ? date('Y-m-d', strtotime($request->date_to)) : 0;


        $result = DB::table('users')
            ->join('timeLog', 'users.id', '=','timeLog.user_id')
            ->join('departments', 'users.department_id', '=', 'departments.id')
            ->when($id , function ($query, $id) {
                return  $query->where(function (Builder $query) use ($id) {
                    $query->where('users.id','=', $id);
                });
            })
            ->when($search , function ($query, $search) {
                return  $query->where(function (Builder $query) use ($search) {
                    $query->where('users.username','like', '%'.strtolower($search).'%')
                        ->orWhere('users.name', 'like', '%'.strtolower($search).'%');
                });
            })
            ->when($date_from , function ($query, $date_from) {
                return  $query->where(function (Builder $query) use ($date_from) {
                    $query->where('timeLog.check_in','>=', $date_from)
                        ->orWhere('timeLog.check_out','>=', $date_from);
                });
            })
            ->when($date_to , function ($query, $date_to) {
                return  $query->where(function (Builder $query) use ($date_to) {
                    $query->where('timeLog.check_in','<=', $date_to)
                        ->orWhere('timeLog.check_out','<=', $date_to);
                });
            })
            ->selectRaw('users.*, timeLog.check_in as check_in, timeLog.check_out as check_out, timeLog.hours as hours, departments.name as department')
            ->orderBy($sort_field, $sort_order)
            ->limit($limit)
            ->paginate($per_page);

        return $result;
    }

    /**
     * get all active users
     *
     * @return mixed
     */
    public function getActiveUsers()
    {
        $data = User::where('active',1)
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        return $data;
    }

    /**
     * update user's active status
     *
     * @param Request $request
     * @return int
     */
    public function updateActiveStatus(Request $request)
    {
        $curr_date = date("Y-m-d H:i:s");
        $id        = isset($request->id)  ? $request->id  : '';
        $active    = isset($request->active) ? ($request->active ) ? 1 : 0 : '';

        $response = DB::table('users')
            ->where('id', $id)
            ->update(['active' => $active, 'updated_at' => $curr_date]);

        return $response;
    }


    /**
     * get user by its own ID
     *
     * @param Request $request
     * @return mixed
     */
    public function getUserByID(Request $request)
    {
        $id = isset($request->id)  ? $request->id  : '';

        $data = User::where('id',$id)
                ->limit(1)
                ->get();

        return $data;
    }

}
