<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Mail\NotifyMail;
use Illuminate\Support\Facades\Mail;


class AdminController extends Controller
{
    public function adminIndex() {
        $user = Auth::user();
        $list_users = User::all();

        return view('admin-index', compact('user', 'list_users'));
    }

    public function adminSearch(Request $request) {
        $user = Auth::user();
        $email_keyword = $request->email_keyword;
        $name_keyword = $request->name_keyword;

        $list_users = User::EmailSearch($email_keyword)->NameSerch($name_keyword)->get();

        return view('admin-index', compact('user', 'list_users', 'email_keyword', 'name_keyword'));
    }

    public function empowerment($user_id) {
        $update_user = User::find($user_id)->update([
            'is_manager' => true,
        ]);

        return redirect('/admin/index');
    }

    public function revoke($user_id) {
        $update_user = User::find($user_id)->update([
            'is_manager' => false,
        ]);

        return redirect('/admin/index');
    }

    public function adminNotify() {
        return view('admin-notify');
    }

    public function sendNotify(Request $request) {
        if( $request->to == "all" ) {
            $users = User::all();
        }

        $subject = $request->subject;
        $content = $request->content;

        foreach( $users as $user ) {
            Mail::to($user->email)->send(new NotifyMail($subject, $content));
        }

        return redirect('/admin/notify')->with('status', '送信が完了しました!');
    }
}
