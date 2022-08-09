<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Hash;
class UserController extends Controller
{

    protected $view = "karyawan.";
    protected $menu_header = "Karyawan";
    protected $menu_title = "Karyawan";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $users = User::select('*');
        if ($request->role != null) {
            $users->where('role_id', $request->role);
        }
        if ($request->ajax()) {
            if ($request->has("q")) {
                $users = $users->where("displayName", "like", "%" . $request->q . "%")->get();
            } else {
                $users = $users->get();
            }
            return response()->json($users);
        } else {
            $d["users"] = $users->get();
            return view($this->view . "index", $d);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $d["roles"] = Role::all();
        $d["is_edit"] = false;
        return view($this->view . "form", $d);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->displayName = $request->displayName;
        $user->username = $request->username;
        $user->address = $request->address;
        $user->telephone = $request->telephone;
        $user->password = Hash::make($request->password);
        $user->role_id = $request->role_id;
        $user->save();
        return redirect()->back()->with("message","User Dibuat");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $d["menu_header"] = $this->menu_header;
        $d["menu_title"] = $this->menu_title;
        $d["user"] = User::find($id);
        $d["roles"] = Role::all();
        $d["is_edit"] = true;
        return view($this->view . "form", $d);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'displayName' => 'required|min:3|max:50',
            'username' => 'required|min:3|max:50',
            'role_id' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = new User();
        $user->displayName = $request->displayName;
        $user->username = $request->username;
        $user->address = $request->address;
        $user->telephone = $request->telephone;
        $user->password = Hash::make($request->password);
        $user->role_id = $request->role_id;
        return redirect()->back()->with("message","User Diedit");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->back()->with("message","User Dihapus");

    }
}
