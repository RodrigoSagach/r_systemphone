<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FinancesWithdrawalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $withdrawals = \App\Withdrawal::orderBy('id', 'ASC');

        if ($request->has('s'))
            $withdrawals->where('description', 'LIKE', psp($request->input('s')));

        return view('admin.finances.withdrawals.index')
            ->with('withdrawals', $withdrawals->paginate(15));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('admin.finances.withdrawals.create')
            ->with('client', $request->has('user_id') ? \App\User::find($request->input('user_id')) : null);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'to' => 'required|email',
            'email' => 'required|email|exists:users,email',
            'amount' => 'required|digits_between:3,15'
        ]);

        $user = \App\User::where('email', '=', $request->input('email'))->first();

        $user->withdrawals()->create([
            'to' => $request->input('to'),
            'amount' => $request->input('amount') / 100,
            'description' => $request->input('description')
        ]);

        return redirect()->route('admin.finance.withdrawals.index');
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
        \App\Withdrawal::destroy($id);

        return redirect()->route('admin.finance.withdrawals.index');
    }
}
