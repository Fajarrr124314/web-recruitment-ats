<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Candidate\JobForm;
use App\Livewire\Hrd\Dashboard;
use App\Livewire\Hrd\RejectedCandidates;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Home redirect based on login status and role
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->isHrd() 
            ? redirect()->route('hrd.overview') 
            : redirect()->route('candidate.apply');
    }
    return redirect()->route('login');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Candidate applying form page
    Route::get('/candidate/apply', JobForm::class)->name('candidate.apply');
    
    // HRD dashboard page
    Route::get('/hrd/overview', \App\Livewire\Hrd\Overview::class)->name('hrd.overview');
    Route::get('/hrd/dashboard', Dashboard::class)->name('hrd.dashboard');
    Route::get('/hrd/requirements', \App\Livewire\Hrd\Requirements::class)->name('hrd.requirements');
    Route::get('/hrd/rejected', RejectedCandidates::class)->name('hrd.rejected');
    
    // Secure Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});

