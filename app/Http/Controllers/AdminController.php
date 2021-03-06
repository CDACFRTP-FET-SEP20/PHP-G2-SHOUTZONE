<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('adminLogin');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminApproval(Request $request, $id)
    {
        $user = User::find($id);
        if ($user->is_approved == false) {
            $user->is_approved = true;
            $user->save();
            $message = 'User  ' . $user->bio->name . ' has been approved.';
            return Redirect::to('/userlist')->with(['success' => $message]);
        } else {
            $user->is_approved = false;
            $user->save();
            $message = 'User  ' . $user->bio->name . ' has been disapproved.';
            return Redirect::to('/userlist')->with(['success' => $message]);
        }
    }
    public function userList()
    {
        $users = User::all()->where('role', 'user');
        return view('home', ['users' => $users]);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    function delete($id)
    {
        $user = User::find($id);
        $message = 'User ' . $user->bio->name . ' has been deleted.';

        $user->delete();
        return Redirect::to('/userlist')->with(['success' => $message]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
