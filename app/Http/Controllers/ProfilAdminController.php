<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilAdminController extends Controller
{
    public function edit(User $user)
    {
        return view('admin.ubahProfil', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->all();

        if ($request->file('foto')) {
            $image = $request->file('foto');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put('admin/' . $imageName, file_get_contents($image));

            $data['foto'] = $imageName;
        } else {
            $data['foto'] = $user->foto;
        }

        // dd($request->file('foto'));

        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            $data['password'] = $user->password;
        }

        $user->update($data);

        Alert::toast('Profil Admin Berhasil Di Edit', 'success');

        return redirect('/admin/dashboard');
    }
}
