<?php

namespace App\Http\Controllers;

use App\NoticeBoard;
use App\SendNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class NoticeBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $notices = NoticeBoard::all();
        return view('admin.noticeboard.index', compact('notices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.noticeboard.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $this->validate($request, [
            'title' => 'required|max:100',
            'description' => 'required|min:15|max:500',
        ]);
        $milliseconds = round(microtime(true) * 1000);
        $notice = new NoticeBoard();
        $notice->title = $request->title;
        $notice->description = $request->description;
        $notice->time = $milliseconds;
        $notice->save();

        $users = User::all();
        foreach ($users as $user) {
            if ($user->hasRole('client')) {
                $sendNotification = new SendNotification();
                $sendNotification->sendPushNotification($user->fcmKey,
                    "New Notification from administration",
                    $request->title,
                    1,
                    'noticeboard'
                );
            }
        }


        return redirect::to('admin/noticeboard')->withMessage('New notice has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $notice = NoticeBoard::find($id);
        return view('admin.noticeboard.edit', compact('notice'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $notice = NoticeBoard::find($id);
        $notice->title = $request->title;
        $notice->description = $request->description;
        $notice->update();
        return redirect::to('admin/noticeboard')->withMessage('New notice has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $notice = NoticeBoard::find($id)->delete();
    }
}
