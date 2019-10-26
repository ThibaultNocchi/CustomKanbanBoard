<?php

namespace App\Http\Controllers;

use App\Card;
use App\Exceptions\NoLineException;
use App\Exceptions\ValidationFailedException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Auth::user()->cards()->ordered()->get());
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:127'
        ]);

        if($validator->fails()){
            throw new ValidationFailedException('', $validator->errors());
        }

        return response()->json(Card::register(Auth::user(), urldecode($request->name)));
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
     * @param  string $name
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $card = Auth::user()->cards()->find($id);
        if($card === null) throw new NoLineException('No card as asked in this board.');
        $card->remove_card();
        return response()->json();
    }

    public function switch(int $id1, int $order) {
        $card1 = Auth::user()->cards()->find($id1);
        if($card1 === null) throw new NoLineException('Cards not found.');
        $card1->switch_to($order);
        return response()->json();
    }
}
