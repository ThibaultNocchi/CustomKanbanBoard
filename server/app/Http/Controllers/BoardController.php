<?php

namespace App\Http\Controllers;

use App\Board;
use App\Exceptions\NoLineException;
use App\Exceptions\ValidationFailedException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Auth::user());
    }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:127'
        ]);

        if($validator->fails()){
            throw new ValidationFailedException('', $validator->errors());
        }

        return response()->json(Board::register(urldecode($request->name)));

    }

    /**
     * Display the specified resource.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function show(string $code)
    {
        $board = Board::login($code);
        if(!$board) throw new NoLineException('No board found.');
        return response()->json($board);
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  \App\Board  $board
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit(Board $board)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Board $board)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function destroy(Board $board)
    {
        //
    }

}
