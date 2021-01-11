<?php

namespace App\Http\Controllers;

use App\Departments;
use App\Tickets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FindController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function search(Request $request){
        $tickets = Tickets::where('subject', 'like', '%' .$request->q . '%')->paginate(15);
        $search_term = $request->q;

        $open = Tickets::where(['assigned_to' => Auth::id(), 'status'=> 'open'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'open'])->count();
        $replied = Tickets::where(['assigned_to' => Auth::id(), 'status'=> 'replied'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'replied'])->count();
        $resolved = Tickets::where(['assigned_to' => Auth::id(), 'status'=> 'resolved'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'resolved'])->count();
        $processing = Tickets::where(['assigned_to' => Auth::id(), 'status'=> 'processing'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'processing'])->count();
        $closed = Tickets::where(['assigned_to' => Auth::id(), 'status'=> 'closed'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'closed'])->count();
        $pending = Tickets::where(['assigned_to' => Auth::id(), 'status'=> 'pending'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'pending'])->count();
        $departments = Departments::all();
        $tickets_depart = Tickets::where('assigned_to', Auth::id())->orWhere('user_id', Auth::id())->get();

        return view('tickets.index', compact('tickets', 'search_term', 'open','replied','processing','resolved', 'closed', 'pending', 'departments', 'tickets_depart'));
    }

    public function status($status){
        $tickets = Tickets::where('status', $status)->paginate(15);
        $open = Tickets::where(['assigned_to' => Auth::id(), 'status'=> 'open'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'open'])->count();
        $replied = Tickets::where(['assigned_to' => Auth::id(), 'status'=> 'replied'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'replied'])->count();
        $resolved = Tickets::where(['assigned_to' => Auth::id(), 'status'=> 'resolved'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'resolved'])->count();
        $processing = Tickets::where(['assigned_to' => Auth::id(), 'status'=> 'processing'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'processing'])->count();
        $closed = Tickets::where(['assigned_to' => Auth::id(), 'status'=> 'closed'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'closed'])->count();
        $pending = Tickets::where(['assigned_to' => Auth::id(), 'status'=> 'pending'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'pending'])->count();
        $departments = Departments::all();
        $tickets_depart = Tickets::where('assigned_to', Auth::id())->orWhere('user_id', Auth::id())->get();

        return view('tickets.index', compact('tickets', 'open','replied', 'closed','resolved', 'processing', 'pending', 'departments', 'tickets_depart'));

    }


    public function department($id){

        $tickets = Tickets::where('department_id', $id)->paginate(15);

        $open = Tickets::where(['assigned_to' => Auth::id(), 'status'=> 'open'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'open'])->count();
        $replied = Tickets::where(['assigned_to' => Auth::id(), 'status'=> 'replied'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'replied'])->count();
        $resolved = Tickets::where(['assigned_to' => Auth::id(), 'status'=> 'resolved'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'resolved'])->count();
        $processing = Tickets::where(['assigned_to' => Auth::id(), 'status'=> 'processing'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'processing'])->count();
        $closed = Tickets::where(['assigned_to' => Auth::id(), 'status'=> 'closed'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'closed'])->count();
        $pending = Tickets::where(['assigned_to' => Auth::id(), 'status'=> 'pending'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'pending'])->count();
        $departments = Departments::all();
        $tickets_depart = Tickets::where('assigned_to', Auth::id())->orWhere('user_id', Auth::id())->get();

        return view('tickets.index', compact('tickets', 'open','replied', 'closed','resolved', 'processing','pending', 'departments', 'tickets_depart'));

    }
}
