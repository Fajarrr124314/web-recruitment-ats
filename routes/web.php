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
    Route::get('/hrd/process/{stage}', \App\Livewire\Hrd\CandidateStage::class)->name('hrd.process');
    Route::get('/hrd/requirements', \App\Livewire\Hrd\Requirements::class)->name('hrd.requirements');
    Route::get('/hrd/hired', \App\Livewire\Hrd\HiredCandidates::class)->name('hrd.hired');
    Route::get('/hrd/rejected', RejectedCandidates::class)->name('hrd.rejected');
    Route::get('/hrd/activity-logs', \App\Livewire\Hrd\ActivityLogs::class)->name('hrd.activity-logs');
    Route::get('/hrd/analytics', \App\Livewire\Hrd\Analytics::class)->name('hrd.analytics');
    
    // Secure Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});

// Secure & Bulletproof File Viewer Route (Solusi Anti-403 untuk Lokal dan Hostinger)
Route::get('/storage/dynamic_files/{filename}', function ($filename) {
    $path = 'dynamic_files/' . $filename;
    
    if (!Storage::disk('public')->exists($path)) {
        abort(404, 'File tidak ditemukan.');
    }
    
    $file = Storage::disk('public')->get($path);
    $mimeType = Storage::disk('public')->mimeType($path);
    
    return Response::make($file, 200, [
        'Content-Type' => $mimeType,
        'Content-Disposition' => 'inline; filename="' . $filename . '"',
    ]);
});


