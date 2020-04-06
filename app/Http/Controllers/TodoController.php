<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TodoController extends Controller
{
    /**
     * get user associated todos
     *
     * @param Request $request
     * @return mixed
     */
    public function getTodosByUserID(Request $request)
    {
        $user_id  = isset($request->user_id)  ? $request->user_id  : '';

        $data = Todo::where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return $data;
    }

    /**
     * add a new todo-item
     *
     * @param Request $request
     * @return Todo
     */
    public function addTodo(Request $request)
    {
        var_dump($request['title']);
        var_dump($request['title']);

        $curr_date = date("Y-m-d H:i:s");
        $user_id   = isset($request['user_id'])  ? $request['user_id']  : '';
        $title     = isset($request['title'] )  ? $request['title']  : '';

        $todo = new Todo();
        $todo->user_id = $user_id;
        $todo->title = $title;
        $todo->completed = '0';
        $todo->created_at = $curr_date;
        $todo->save();
        return $todo;
    }

    /**
     * change todo-item completed value
     *
     * @param Request $request
     * @return int
     */
    public function updateTodo(Request $request)
    {

        $curr_date = date("Y-m-d H:i:s");
        $id        = isset($request->id)  ? $request->id  : '';
        $completed = isset($request->completed) ? ($request->completed ) ? 1 : 0 : '';

        $response = DB::table('todos')
            ->where('id', $id)
            ->update(['completed' => $completed, 'updated_at' => $curr_date]);

        return $response;
    }

    /**
     * remove a todo-item
     *
     * @param Request $request
     * @return string
     */
    public function removeTodo(Request $request)
    {
        $id   = isset($request->id)  ? $request->id  : '';
        $todo = Todo::findOrFail($id);
        $res = $todo->delete();
        return strval($res);
    }

    /**
     * remove completed todo-items
     * @param Request $request
     * @return mixed
     */
    public function removeCompletedTodos(Request $request)
    {
        $ids = $request['ids'];
        $res=Todo::whereIn('id', $ids)->delete();
        return $res;
    }
}
