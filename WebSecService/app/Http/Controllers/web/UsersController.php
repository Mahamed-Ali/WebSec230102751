<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function list(Request $request)
    {
        $query = User::query();

        if ($request->keywords) {
            $query->where('name', 'like', '%' . $request->keywords . '%');
        }

        if ($request->id_filter) {
            $query->where('id', $request->id_filter);
        }

        $users = $query->paginate(10);

        $allUsers = User::all();

        return view('users.list', [
            'users' => $users,
            'allUsers' => $allUsers
        ]);
    }

    public function add()
    {
        $user = new User();
        return view('users.form', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.form', compact('user'));
    }

    public function save(Request $request, User $user = null)
    {
        $user = $user ?? new User();

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => !$user->id ? 'required|min:6' : 'nullable'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('users_list')->with('success', 'User saved successfully!');
    }

    public function delete(User $user)
    {
        $user->delete();

        return redirect()->route('users_list')->with('success', 'User deleted successfully!');
    }
}
