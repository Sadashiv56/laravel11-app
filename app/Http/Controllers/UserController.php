<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use DataTables;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Validator;
class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::all();
            return DataTables::of($data)
                ->addColumn('status', function (User $data) {
                    return $data->status == 1 ? 'Active' : 'Inactive';
                })
                ->addColumn('action', function (User $data) {
                    $edit = route('users.edit', $data->id);
                    $deleteLink = route('users.destroy', $data->id);
                    $btn = '<a href="' . $edit . '" class="edit btn btn-success btn-sm">Edit</a>';
                    $btn .= '<form action="' . $deleteLink . '" method="POST" style="display:inline-block;" class="delete-form">';
                    $btn .= csrf_field();
                    $btn .= method_field('DELETE');
                    $btn .= '<button type="submit" class="btn btn-danger btn-sm delete-button">Delete</button>';
                    $btn .= '</form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('users.index');
    }
    public function create(): View
    {
        return view('users.create');
    }
    public function store(UserRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        return redirect()->route('users.index')->with('success', 'User created successfully');
    }
    public function edit($id): View
    {
        $user = User::find($id);
        return view('users.edit', compact('user'));
    }
   public function update(Request $request, $id): RedirectResponse
    {
        // Validate the request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'mobile' => 'required|digits_between:10,15',
            'status' => 'required',
            // 'password' => 'nullable|min:6|confirmed', // The 'confirmed' rule will match 'password_confirmation' field
        ]);

        // Retrieve all input data
        $input = $request->all();

        // Check if the password is provided and hash it
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            // Remove the password field from the input to prevent it from updating
            $input = Arr::except($input, ['password']);
        }

        // Find the user by ID and update it
        $user = User::find($id);
        $user->update($input);

        // Redirect back to the users index with a success message
        return redirect()->route('users.index')
                            ->with('success','User updated successfully');
    }
    public function destroy(string $id)
    {
        if (request()->ajax()) {
            try {
                $users = user::findOrFail($id);
                $users->delete();
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        return redirect()->route('users.index')
                         ->with('success', 'User deleted successfully');
    }
    public function checkEmail(Request $request)
    {
        $emailExists = User::where('email', $request->email)->exists();

        return response()->json(!$emailExists);

    }

}
