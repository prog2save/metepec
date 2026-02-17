<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Http\Requests\UsuarioStore;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios =  Usuario::orderBy('id', 'desc')->paginate(10);
        return view('pages.users.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UsuarioStore  $request)
    {
        $input = $request->validated();
        $input['password'] = bcrypt($input['password']);
        Usuario::create($input);
        return redirect()->back()->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('pages.users.edit', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UsuarioStore $request, string $id)
    {
        $input = $request->validated();
        $input['password'] = bcrypt($input['password']);
        Usuario::where('id', $id)->update($input);
        return redirect()->back()->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Usuario::destroy($id);
        return redirect()->back()->with('success', 'Usuario eliminado exitosamente.');
    }
}
