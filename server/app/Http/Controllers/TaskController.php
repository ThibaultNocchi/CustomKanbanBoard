<?php

namespace App\Http\Controllers;

use App\Exceptions\NoLineException;
use App\Exceptions\ValidationFailedException;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(int $card_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:128',
        ]);

        if ($validator->fails()) {
            throw new ValidationFailedException('', $validator->errors());
        }

        $card = Auth::user()->cards()->find($card_id);
        if ($card === null) throw new NoLineException('No card found.');

        return response()->json(Task::register($card, urldecode($request->name)));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Auth::user()->tasks()->find($id);
        if($task === null) throw new NoLineException('No task as asked in this board.');
        $task->remove_task();
        return response()->json();
    }

    public function editTask(int $id, Request $request)
    {
        $task = Auth::user()->tasks()->find($id);
        if ($task === null) throw new NoLineException('No task as asked in board.');

        $validator = Validator::make($request->all(), [
            'description' => 'string',
            'name' => 'sometimes|required|string',
            'color' => 'string|size:6'
        ]);

        if ($validator->fails()) {
            throw new ValidationFailedException('', $validator->errors());
        }

        if ($request->has('description')) {
            if ($request->description !== "") $task->description = $request->description;
            else $task->description = null;
        }

        if($request->has('name')) {
            $task->name = $request->name;
        }

        if($request->has('color')) {
            $task->color = $request->color;
        }

        $task->save();

        return response()->json($task);
    }
}
