<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{

    public function index(Request $request)
    {
        return view('profile.perfil');
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function uploadAvatar(Request $request)
    {
        $user = auth()->user();
        
        // Validação rigorosa do arquivo
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,jpg,png,gif|max:2048'
        ]);
        
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            
            // Validação adicional de segurança
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $extension = strtolower($file->getClientOriginalExtension());
            
            if (!in_array($extension, $allowedExtensions)) {
                return redirect()->back()->with('error', 'Tipo de arquivo não permitido. Use apenas JPG, PNG ou GIF.');
            }
            
            // Verificar se não é um arquivo PHP disfarçado
            $mimeType = $file->getMimeType();
            $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            
            if (!in_array($mimeType, $allowedMimes)) {
                return redirect()->back()->with('error', 'Arquivo inválido detectado.');
            }
            
            $filename = uniqid() . '.' . $extension;
            $destination = public_path('uploads/avatars');
            
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            
            if ($file->move($destination, $filename)) {
                $user->avatar = '/uploads/avatars/' . $filename;
                $user->save();
                return redirect()->back()->with('success', 'Avatar atualizado com sucesso!');
            } else {
                return redirect()->back()->with('error', 'Erro ao salvar o arquivo.');
            }
        } else {
            return redirect()->back()->with('error', 'Não foi possível alterar o avatar. Tente novamente!');
        }
    }
}
