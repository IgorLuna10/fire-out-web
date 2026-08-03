<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user      = auth()->user();
        $addresses = $user->addresses()->get();
        $orders    = $user->orders()->with('items')->take(5)->get();
        return view('profile.index', compact('user', 'addresses', 'orders'));
    }

    public function updateInfo(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|min:2|max:100',
            'phone' => 'required|string|min:8|max:20',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ]);
        auth()->user()->update($data);
        return back()->with('success', 'Dados atualizados.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Senha atual incorreta.']);
        }

        auth()->user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Senha alterada com sucesso.');
    }

    public function storeAddress(Request $request)
    {
        $data = $request->validate([
            'label'        => 'required|string|max:50',
            'street'       => 'required|string|max:150',
            'number'       => 'required|string|max:20',
            'complement'   => 'nullable|string|max:80',
            'neighborhood' => 'required|string|max:100',
            'city'         => 'required|string|max:100',
            'state'        => 'required|string|size:2',
            'zipcode'      => 'required|string|max:9',
            'is_default'   => 'boolean',
        ]);

        $user = auth()->user();

        if (!empty($data['is_default'])) {
            $user->addresses()->update(['is_default' => false]);
        }

        // if this is first address, make it default
        if ($user->addresses()->count() === 0) {
            $data['is_default'] = true;
        }

        $user->addresses()->create($data);
        return back()->with('success', 'Endereço adicionado.');
    }

    public function deleteAddress(Address $address)
    {
        abort_if($address->user_id !== auth()->id(), 403);
        $address->delete();
        return back()->with('success', 'Endereço removido.');
    }

    public function setDefaultAddress(Address $address)
    {
        abort_if($address->user_id !== auth()->id(), 403);
        auth()->user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);
        return back()->with('success', 'Endereço padrão atualizado.');
    }
}
