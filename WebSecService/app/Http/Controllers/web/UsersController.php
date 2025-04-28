<?php
namespace App\Http\Controllers\Web;

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
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use Carbon\Carbon;


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

    public function doRegister(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => ['required', 'string', 'min:5'],
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->input())->withErrors('Invalid registration information.');
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        $user->assignRole('Customer');

        // Send Email Verification
        $title = "Verification Link";
        $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
        $link = route('verify', ['token' => $token]);
        Mail::to($user->email)->send(new VerificationEmail($link, $user->name));

        return redirect('/');
    }

    public function login(Request $request) {
        return view('users.login');
    }

    public function doLogin(Request $request) {
        if(!Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');
    
        $user = User::where('email', $request->email)->first();
        Auth::setUser($user);
    
        if(!$user->email_verified_at) {
            Auth::logout(); 
            return redirect()->back()->withInput($request->input())->withErrors('Your email is not verified.');
        }

        if (session()->has('temporary_password')) {
            session()->forget('temporary_password');
            return redirect()->route('password.change');
        }
    
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

    public function verify(Request $request) 
    {

        $decryptedData = json_decode(Crypt::decryptString($request->token), true);
        $user = User::find($decryptedData['id']);
        if(!$user) abort(401);
        $user->email_verified_at = Carbon::now();
        $user->save();
        return view('users.verified', compact('user'));
       }
    
    public function forgotPasswordPage()
    {
        return view('users.forgot_password');
    }  
    
    public function sendTemporaryPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'This email is not registered.']);
        }

        $temporaryPassword = \Str::random(8); 
        $user->password = bcrypt($temporaryPassword);
        $user->save(); 

        session(['temporary_password' => true]);

        Mail::raw('Your temporary password is: ' . $temporaryPassword, function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Temporary Password');
        });

        return redirect('/login')->with('success', 'Temporary password sent to your email.');
    }


    public function changePasswordPage()
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        return view('users.change_password');
    }

    public function saveNewPassword(Request $request)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $request->validate([
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->letters()->numbers()->symbols()],
        ]);

        $user = auth()->user();
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect('/')->with('success', 'Password changed successfully.');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'This email is not registered.']);
        }

        $token = Crypt::encryptString(json_encode([
            'id' => $user->id,
            'email' => $user->email,
            'time' => now()->addMinutes(30) // اللينك صالح 30 دقيقة
        ]));

        $resetLink = route('password.reset', ['token' => $token]);

        Mail::raw('Click here to reset your password: ' . $resetLink, function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Reset Password');
        });

        return redirect('/login')->with('success', 'Reset password link sent to your email.');
    }

    public function resetPasswordPage(Request $request)
    {
        $token = $request->token;
        return view('users.reset_password', compact('token'));
    }

    public function resetPasswordSave(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->letters()->numbers()->symbols()],
        ]);

        $decrypted = json_decode(Crypt::decryptString($request->token), true);

        $user = User::find($decrypted['id']);

        if (!$user || $user->email != $decrypted['email']) {
            return redirect('/login')->withErrors(['email' => 'Invalid or expired reset link.']);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return redirect('/login')->with('success', 'Password reset successfully.');
    }


}
