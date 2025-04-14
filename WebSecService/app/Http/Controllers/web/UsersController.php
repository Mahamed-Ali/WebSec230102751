<?php
namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Artisan;
use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller {

	use ValidatesRequests;

    public function list(Request $request) {
        if(!auth()->user()->hasPermissionTo('show_users')) abort(401);
        $query = User::select('*');
        $query->when($request->keywords, fn($q)=> $q->where("name", "like", "%$request->keywords%"));
        $users = $query->get();
        return view('users.list', compact('users'));
    }

	public function register(Request $request) {
        return view('users.register');
    }

    public function doRegister(Request $request) {
    	try {
    		$this->validate($request, [
		        'name' => ['required', 'string', 'min:5'],
		        'email' => ['required', 'email', 'unique:users'],
		        'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
	    	]);
    	} catch(\Exception $e) {
    		return redirect()->back()->withInput($request->input())->withErrors('Invalid registration information.');
    	}

    	$user = new User();
	    $user->name = $request->name;
	    $user->email = $request->email;
	    $user->password = bcrypt($request->password);
	    $user->save();
        $user->assignRole('Customer');
        return redirect('/');
    }

    public function login(Request $request) {
        return view('users.login');
    }

    public function doLogin(Request $request) {
    	if(!Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');
        Auth::setUser(User::where('email', $request->email)->first());
        return redirect('/');
    }

    public function doLogout(Request $request) {
    	Auth::logout();
        return redirect('/');
    }

    public function profile(Request $request, User $user = null) {
        $user = $user ?? auth()->user();
        if(auth()->id()!=$user->id && !auth()->user()->hasPermissionTo('show_users')) abort(401);

        $permissions = [];
        foreach($user->permissions as $permission) $permissions[] = $permission;
        foreach($user->roles as $role) foreach($role->permissions as $permission) $permissions[] = $permission;

        return view('users.profile', compact('user', 'permissions'));
    }

    public function edit(Request $request, User $user = null) {
        $user = $user ?? auth()->user();
        if(auth()->id()!=$user->id && !auth()->user()->hasPermissionTo('edit_users')) abort(401);

        $roles = Role::all()->map(function ($role) use ($user) {
            $role->taken = $user->hasRole($role->name);
            return $role;
        });

    


        $permissions = Permission::all()->map(function ($permission) use ($user) {
            $permission->taken = $user->permissions->contains('id', $permission->id);
            return $permission;
        });

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function createEmployee()
    {
        return view('admin.create_employee');
    }

    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'credit' => 0, 
        ]);

        $user->assignRole('employee'); 
        return redirect()->route('dashboard')->with('success', 'Employee created successfully!');
    }


    public function save(Request $request, User $user) {
        if(auth()->id()!=$user->id && !auth()->user()->hasPermissionTo('edit_users')) abort(401);

        $user->name = $request->name;
        $user->save();

        if(auth()->user()->hasPermissionTo('edit_users')) {
            $user->syncRoles($request->roles ?? []);
            $user->syncPermissions($request->permissions ?? []);
            Artisan::call('cache:clear');
        }

        return redirect()->route('profile', ['user' => $user->id]);
    }

    public function delete(Request $request, User $user) {
        if(!auth()->user()->hasPermissionTo('delete_users')) abort(401);
        $user->delete();
        return redirect()->route('users');
    }

    public function editPassword(Request $request, User $user = null) {
        $user = $user ?? auth()->user();
        if(auth()->id()!=$user->id && !auth()->user()->hasPermissionTo('edit_users')) abort(401);
        return view('users.edit_password', compact('user'));
    }

    public function savePassword(Request $request, User $user) {
        if(auth()->id()==$user->id) {
            $this->validate($request, [
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);

            if(!Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
                Auth::logout();
                return redirect('/');
            }
        } else if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);

        $user->password = bcrypt($request->password);
        $user->save();
        return redirect()->route('profile', ['user'=>$user->id]);
    }

    public function listCustomers()
    {
        if (!auth()->user()->hasRole('Employee')) {
            abort(401);
        }

        $users = User::role('Customer')->get();

        return view('users.customers', compact('users'));
    }

    public function addCredit(User $user) {
        if (!auth()->user()->hasPermissionTo('charge_credit')) {
            abort(401);
        }
        return view('users.add_credit', compact('user'));
    }


    public function saveCredit(Request $request, User $user) {
        if (!auth()->user()->hasPermissionTo('charge_credit')) {
            abort(401);
        }
        $validated = $request->validate([
            'credit' => ['required', 'numeric', 'min:1']
        ]);
        $user->credit += $request->credit; 
        $user->save();

        return redirect()->route('list_customers')->with('success', 'Credit added successfully.');
    }
   
    
       

}
