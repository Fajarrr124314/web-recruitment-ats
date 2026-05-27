<?php

namespace App\Livewire\Hrd;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ProfileModal extends Component
{
    public bool $isOpen = false;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected array $rules = [];

    protected $listeners = [
        'openProfileModal' => 'openModal',
    ];

    public function openModal()
    {
        $user = Auth::user();
        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
            $this->password = '';
            $this->password_confirmation = '';
            $this->isOpen = true;
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetErrorBag();
    }

    public function save()
    {
        $user = Auth::user();
        if (!$user) return;

        $validatedData = $this->validate([
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama lengkap minimal 3 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user->name = $this->name;
        $user->email = $this->email;

        if (!empty($this->password)) {
            $user->password = Hash::make($this->password);
        }

        $user->save();

        $this->isOpen = false;
        $this->password = '';
        $this->password_confirmation = '';

        // Dispatch a browser event / Livewire event to notify success via existing Toast
        $this->dispatch('show-toast', message: 'Profil Anda berhasil diperbarui!', type: 'success');
        
        // Log Recruiter activity if logs exist
        try {
            \App\Models\RecruiterActivityLog::create([
                'user_id' => $user->id,
                'action' => 'Update Profil',
                'description' => 'Memperbarui informasi profil akun HRD.',
            ]);
        } catch (\Exception $e) {
            // Ignore if table/log system is not available
        }
    }

    public function render()
    {
        return view('livewire.hrd.profile-modal');
    }
}
