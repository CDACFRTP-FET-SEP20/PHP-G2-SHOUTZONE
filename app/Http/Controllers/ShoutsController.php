<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shout;
use App\Models\User;
use App\Models\Friends;
use App\Models\Report;

class ShoutsController extends Controller
{
    public function uploadmedia(Request $req)
    {

        $shoutsUpload = new Shout();

        $shoutsUpload->user_id = $req->user_id;
        $shoutsUpload->shoutType = $req->shoutType;
        $shoutsUpload->shoutText = $req->shoutText;
        if ($file = $req->hasFile('shoutMedia')) {
            $file = $req->file('shoutMedia');
            $fileName = 'Shout_' . $req->user_id . time() . "." . $req->file('shoutMedia')->getClientOriginalExtension();
            $destinationPath = public_path() . '/Shouts/';
            $file->move($destinationPath, $fileName);
            $shoutsUpload->shoutMedia = '/Shouts/' . $fileName;
        }
        $shoutsUpload->save();
        return response()->json(['message' => 'media Uploaded Successfully']);
    }

    public function list()
    {

        return  Shout::all();
    }
    public function allShouts()
    {
        $users = User::all()->where('role', 'user');
        $userIds = $users->modelKeys();
        $allShouts = Shout::whereIn('user_id', $userIds)->get();

        return view('shouts', ['shouts' => $allShouts]);
    }
    public function shoutById($id)
    {
        $user = User::find($id);
        $shoutsUpload = $user->shout;
        foreach ($shoutsUpload as $key => $value) {
            $value->user;
            $value->likes;
            $value->report;
            $value->comments;
        }
        return $shoutsUpload;
    }

    public function friendsShout($id)
    {

        $friends = Friends::where('sender', $id)
            ->where('approved', 1)
            ->orWhere('reciever', $id)
            ->where('approved', 1)->get();
        $friendsArr = [];
        foreach ($friends as $key => $value) {
            if (($value->user->id) != $id) {
                array_push($friendsArr, [$value->user->id]);
            } elseif (($value->user2->id) != $id) {
                array_push($friendsArr, [$value->user2->id]);
            } else {
                array_push($friendsArr);
            }
        }
        $flatten = array_merge(...$friendsArr);
        $shout = Shout::whereIn('user_id', $flatten)->latest()->get();
        foreach ($shout as $key => $value) {
            $value->user;
            $value->likes;
            $value->report;
            $value->comments;
        }
        return response()->json($shout);
    }

    public function deleteshout($id)
    {
        $shoutsUpload = Shout::find($id);
        $shoutsUpload->delete();
        return back();
    }
    public function deleteownshout($id)
    {
        $shoutsUpload = Shout::find($id);
        $shoutsUpload->delete();
    }
    public function reportedShout()
    {
        $reported = Report::all()->groupBy('category');
        return view('reports', ['reports' => $reported]);
    }
}
