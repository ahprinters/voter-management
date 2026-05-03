<?php

use App\Livewire\HouseManager;
use App\Livewire\VillageManager;
use App\Livewire\VoterList;
use App\Livewire\VoterManager; // আপনার ক্লাস বেইজড কম্পোনেন্ট
use App\Models\Voter;
use Illuminate\Support\Facades\Route;

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
        Route::get('/', VoterManager::class)->name('create'); // এই রাউটটি এখন সব CRUD অপারেশন হ্যান্ডেল করবে
        Route::get('/edit/{id}', VoterManager::class)->name('edit'); // এডিট রাউট (এটি একই কম্পোনেন্ট ব্যবহার করবে)
        Route::get('/voter-list', VoterList::class)->name('voter-list');
        // ভোটার কমেন্ট যোগ করার রাউট (এটি আগের মতোই রাখা হয়েছে)
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

    //গ্রাম এবং বাড়ি ম্যানেজার রাউট
    Route::get('/villages', VillageManager::class)->name('village-manager.create');
    Route::get('/houses', HouseManager::class)->name('house-manager.create');

    // ভৌগোলিক সংশ্লিষ্ট রাউট
    Route::livewire('/division/create', 'pages::division.create')->name('division.create');
    Route::livewire('/district/create', 'pages::district.create')->name('district.create');
    Route::livewire('/upazila/create', 'pages::upazila.create')->name('upazila.create');
    Route::livewire('/union/create', 'pages::union.create')->name('union.create');
    Route::livewire('/ward/create', 'pages::ward.create')->name('ward.create');
})->name('auth.');



// settings.php ফাইল লোড করা
if (file_exists(__DIR__ . '/settings.php')) {
    require __DIR__ . '/settings.php';
}
