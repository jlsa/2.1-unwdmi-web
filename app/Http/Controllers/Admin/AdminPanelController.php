<?php

namespace Leertaak5\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Auth;
use Leertaak5\User;
use Leertaak5\Http\Requests;
use Leertaak5\Http\Controllers\Controller;

class AdminPanelController extends Controller
{
    /**
     *
     */
    public function show()
    {
        if (!$this->checkRights()) {
            return redirect('/')
                ->with('error', 'Access denied.');
        }
        return view('admin.home');
    }

    public function createUser(Request $request)
    {
        if (!$this->checkRights()) {
            return redirect('/')
                ->with('error', 'Access denied.');
        }
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|max:255|email|unique:users,email',
            'pass' => 'required|min:8'
        ]);

        $name = $request->name;
        $email = $request->email;
        $pass = bcrypt($request->pass);
        $rights = $request->rights == null ? 0 : 1;

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $pass,
            'rights' => $rights
        ];

        User::create($data);

        return redirect('admin')
            ->with('message', 'Account ""' . $email . '" was created!');
    }

    public function showUser()
    {

    }

    public function showCreateUser()
    {
        if (!$this->checkRights()) {
            return redirect('/')
                ->with('error', 'Access denied.');
        }
        return view('admin.create_user');
    }

    public function checkRights()
    {
        //Check if user is an admin. If not, die and return a message.
        if (Auth::user()->rights == 0) {
            return false;
        }
        return true;
    }
}
