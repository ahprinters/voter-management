<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\VoterManager; // আপনার ক্লাস বেইজড কম্পোনেন্ট
use App\Models\Voter;

// হোম পেজ (পাবলিক)
Route::view('/', 'welcome')->name('home');

// অথেনটিকেটেড এবং ভেরিফাইড রাউট গ্রুপ
Route::middleware(['auth', 'verified'])->group(function () {

    // ড্যাশবোর্ড
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    /**
     * ভোটার ম্যানেজমেন্ট রাউটস (Class-based)
     */
    Route::prefix('voters')->name('voters.')->group(function () {

        // প্রধান CRUD কম্পোনেন্ট (লিস্ট, তৈরি, এডিট এবং ডিলিট এখন একসাথেই হবে)
        Route::get('/', VoterManager::class)->name('index');

        // ভোটার কমেন্ট যোগ করার রাউট (এটি আগের মতোই রাখা হয়েছে)
        Route::get('/voter-comment/{voter}', function (Voter $voter) {
            return view('pages.voter-comment.create', compact('voter'));
                  })->name('voter-comments.create');
    });
});

Route::middleware(['auth'])->group(function () {
    // প্রোফাইল/ড্যাশবোর্ড সংশ্লিষ্ট রাউট...

    // প্রতিষ্ঠান সংশ্লিষ্ট রাউট
    Route::livewire('/primary-school/create', 'pages::primary-school.create')->name('primary-school.create');
    Route::livewire('/mosque/create', 'pages::mosque.create')->name('mosque.create');
    Route::livewire('/temple/create', 'pages::temple.create')->name('temple.create');

    // ভৌগোলিক সংশ্লিষ্ট রাউট
    Route::livewire('/division/create', 'pages::division.create')->name('division.create');
    Route::livewire('/district/create', 'pages::district.create')->name('district.create');
});



// settings.php ফাইল লোড করা
if (file_exists(__DIR__ . '/settings.php')) {
    require __DIR__ . '/settings.php';
}
