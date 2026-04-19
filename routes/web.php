<?php

use Illuminate\Support\Facades\Route;
use App\Models\Voter;

// হোম পেজ (পাবলিক)
Route::view('/', 'welcome')->name('home');

// অথেনটিকেটেড এবং ভেরিফাইড রাউট গ্রুপ
Route::middleware(['auth', 'verified'])->group(function () {

    // ড্যাশবোর্ড
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    /**
     * ভোটার ম্যানেজমেন্ট রাউটস
     */
    Route::prefix('voters')->name('voters.')->group(function () {

        // ভোটার লিস্ট (অবস্থান: pages/voters/voter-list.blade.php)
        Route::livewire('/voter-list', 'pages::voters.voter-list')
            ->name('voter-list');

        // ভোটার যোগ করা
        Route::livewire('/add-voter', 'pages::voters.add-voter')
            ->name('add-voter');

        // ভোটার এডিট করা
        Route::livewire('/edit-voter/{id}', 'pages::voters.add-voter')
            ->name('edit-voter');

        // ভোটার কমেন্ট যোগ করা
        // পাথ: /voters/voter-comment/37
        // ব্লেড ফাইল অবস্থান: resources/views/pages/voter-comment/create.blade.php
        Route::get('/voter-comment/{voter}', function (Voter $voter) {
            return view('pages.voter-comment.create', compact('voter'));
        })->name('voter-comments.create');

    });
});

// settings.php ফাইল লোড করা
if (file_exists(__DIR__ . '/settings.php')) {
    require __DIR__ . '/settings.php';
}
