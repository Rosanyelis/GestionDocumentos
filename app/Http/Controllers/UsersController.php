<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::where('rol_id', '!=', '1')->get();
        return view('usuarios.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'unique:users'],
            'password' => ['required', 'max:8'],
        ],
        [
            'name.required' => 'El campo Nombre es obligatorio',
            'email.required' => 'El campo Correo es obligatorio',
            'email.required' => 'El correo ingresado ya existe.',
            'password.required' => 'El campo Contraseña es obligatorio',
            'password.max' => 'El campo Contraseña debe contener máximo 8 carácteres',
        ]);

        $rol = Rol::where('name', 'Operador')->first();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol_id' => $rol->id,
        ]);

        return redirect('usuarios')->with('success', 'Usuario registrado exitósamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $count = User::where('id', $id)->count();
        if ($count>0) {
            $data = User::where('id', $id)->first();
            return view('usuarios.edit', compact('data'));
        } else {
            return redirect('usuarios')->with('error', 'Problemas para mostrar los datos.');
        }
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
        $count = User::where('id', $id)->count();
        if ($count>0) {
            $request->validate([
                'name' => ['required'],
                'email' => ['required'],
                'password' => ['max:8'],
            ],
            [
                'name.required' => 'El campo Nombre es obligatorio',
                'email.required' => 'El campo Correo es obligatorio',
                'password.max' => 'El campo Contraseña debe contener máximo 8 carácteres',
            ]);

            $registro = User::where('id', $id)->first();
            $registro->name = $request->name;
            $registro->email = $request->email;
            $registro->password = Hash::make($request->password);
            $registro->save();

            return redirect('usuarios')->with('success', 'Usuario Actualizado Exitósamente.');
        } else {
            return redirect('usuarios')->with('error', 'Problemas para encontrar el archivo.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $count = User::where('id', $id)->count();
        if ($count>0) {
            User::where('id', $id)->delete();
            return response()->json(200);
        } else {
            return response()->json(404);
        }
    }
}
