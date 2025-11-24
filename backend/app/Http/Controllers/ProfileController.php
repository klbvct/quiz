<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Показать форму редактирования профиля
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }
    
    /**
     * Обновить информацию профиля
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'birthdate' => ['nullable', 'date', 'before:today'],
        ]);
        
        $user->update($validated);
        
        return redirect()->route('profile.edit')->with('success', 'Профиль успешно обновлен');
    }
    
    /**
     * Обновить пароль
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
        
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);
        
        return redirect()->route('profile.edit')->with('success', 'Пароль успешно изменен');
    }
}
