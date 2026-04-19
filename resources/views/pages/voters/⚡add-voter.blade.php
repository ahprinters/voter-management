<?php

use Livewire\Component;
use App\Models\Voter;

new class extends Component
{
    // প্রপার্টিজ
    public $voterId;
    public $isEditMode = false;

    public $name;
    public $father_name;
    public $mother_name;
    public $house_name;
    public $voter_number;
    public $current_location;

    /**
     * মাউন্ট মেথড: এডিট করার সময় ডাটাবেস থেকে ডাটা নিয়ে আসে।
     * রাউটে যদি ID থাকে তবে এটি এডিট মোডে কাজ করবে।
     */
    public function mount($id = null)
    {
        if ($id) {
            $voter = Voter::findOrFail($id);
            $this->voterId = $id;
            $this->isEditMode = true;

            $this->name = $voter->name;
            $this->father_name = $voter->father_name;
            $this->mother_name = $voter->mother_name;
            $this->house_name = $voter->house_name;
            $this->voter_number = $voter->voter_number;
            $this->current_location = $voter->current_location;
        }
    }

    /**
     * ডাটা সংরক্ষণ বা আপডেট করার মেইন ফাংশন।
     */
    public function saveVoter()
    {
        // ভ্যালিডেশন (এডিট করার সময় বর্তমান ইউনিক আইডি বাদ দেওয়া হয়েছে)
        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'house_name' => 'required|string|max:255',
            'voter_number' => 'required|string|max:255|unique:voters,voter_number,' . $this->voterId,
            'current_location' => 'required|string|max:255',
        ]);

        if ($this->isEditMode) {
            // আপডেট লজিক
            $voter = Voter::find($this->voterId);
            $voter->update($validatedData);
            session()->flash('message', 'ভোটার তথ্য সফলভাবে আপডেট করা হয়েছে!');
        } else {
            // নতুন তৈরির লজিক
            Voter::create($validatedData);
            session()->flash('message', 'ভোটার সফলভাবে যোগ করা হয়েছে!');
            $this->reset();
        }
    }
};
?>

<div class="p-6 max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            {{ $isEditMode ? 'ভোটার তথ্য আপডেট' : 'নতুন ভোটার যুক্ত করুন' }}
        </h2>
        {{-- এখানে আপনার লিস্ট পেজের রাউট নেম দিবেন --}}
        <a href="{{ route('voters.voter-list') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition text-sm">
            তালিকায় ফিরে যান
        </a>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="saveVoter" class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-6 rounded-lg shadow">
        <div>
            <label class="block text-sm font-medium text-gray-700">নাম</label>
            <input type="text" wire:model="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">ভোটার নম্বর</label>
            <input type="text" wire:model="voter_number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error('voter_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">পিতার নাম</label>
            <input type="text" wire:model="father_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error('father_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">মাতার নাম</label>
            <input type="text" wire:model="mother_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error('mother_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">বাড়ির নাম</label>
            <input type="text" wire:model="house_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error('house_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">বর্তমান অবস্থান</label>
            <textarea wire:model="current_location" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
            @error('current_location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="md:col-span-2 mt-4">
            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition font-bold">
                {{ $isEditMode ? 'তথ্য আপডেট করুন' : 'ভোটার সংরক্ষণ করুন' }}
            </button>
        </div>
    </form>
</div>
