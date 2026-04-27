<?php

use App\Models\PrimarySchools;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    // Form fields
    public $primary_school_id, $name, $address, $headmaster_name, $president_name, $phone_number, $comments;

    public $isEditing = false;
    public $search = '';

    // Validation rules
    protected $rules = [
        'name' => 'required|string|min:3',
        'address' => 'required|string',
        'phone_number' => 'nullable|string',
    ];

    // Reset fields
    public function resetFields() {
        $this->reset(['name', 'address', 'headmaster_name', 'president_name', 'phone_number', 'comments', 'primary_school_id', 'isEditing']);
    }

    // Create or Update
    public function save() {
        $this->validate();

        PrimarySchools::updateOrCreate(['id' => $this->primary_school_id], [
            'name' => $this->name,
            'address' => $this->address,
            'headmaster_name' => $this->headmaster_name,
            'president_name' => $this->president_name,
            'phone_number' => $this->phone_number,
            'comments' => $this->comments,
        ]);

        session()->flash('message', $this->primary_school_id ? 'প্রাথমিক বিদ্যালয়ের তথ্য আপডেট হয়েছে!' : 'নতুন প্রাথমিক বিদ্যালয় যোগ করা হয়েছে!');
        $this->resetFields();
    }

    // Edit mode
    public function edit($id) {
        $primary_school = PrimarySchools::findOrFail($id);
        $this->primary_school_id = $id;
        $this->name = $primary_school->name;
        $this->address = $primary_school->address;
        $this->headmaster_name = $primary_school->headmaster_name;
        $this->president_name = $primary_school->president_name;
        $this->phone_number = $primary_school->phone_number;
        $this->comments = $primary_school->comments;
        $this->isEditing = true;
    }

    // Delete
    public function delete($id) {
        PrimarySchools::find($id)->delete();
        session()->flash('message', 'প্রাথমিক বিদ্যালয়ের তথ্য মুছে ফেলা হয়েছে!');
    }

    // Data for the view
    public function with() {
        return [
            'primary_schools' => PrimarySchools::where('name', 'like', "%{$this->search}%")
                        ->orWhere('address', 'like', "%{$this->search}%")
                        ->latest()
                        ->paginate(10),
        ];
    }
}; ?>

<div class="p-6">
    @if (session()->has('message'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-4 shadow rounded-lg h-fit">
            <h3 class="text-lg font-bold mb-4">{{ $isEditing ? 'তথ্য সংশোধন' : 'নতুন প্রাথমিক বিদ্যালয় যোগ করুন' }}</h3>
            <form wire:submit.prevent="save" class="space-y-3">
                <input type="text" wire:model="name" placeholder="প্রাথমিক বিদ্যালয়ের নাম" class="w-full border rounded p-2">
                <input type="text" wire:model="address" placeholder="ঠিকানা" class="w-full border rounded p-2">
                <input type="text" wire:model="headmaster_name" placeholder="প্রধান শিক্ষকের নাম" class="w-full border rounded p-2">
                <input type="text" wire:model="president_name" placeholder="প্রধান কর্মকর্তার নাম" class="w-full border rounded p-2">
                <input type="text" wire:model="phone_number" placeholder="ফোন নম্বর" class="w-full border rounded p-2">
                <textarea wire:model="comments" placeholder="মন্তব্য" class="w-full border rounded p-2"></textarea>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                        {{ $isEditing ? 'আপডেট' : 'সংরক্ষণ' }}
                    </button>
                    @if($isEditing)
                        <button type="button" wire:click="resetFields" class="bg-gray-400 text-white px-4 py-2 rounded">বাতিল</button>
                    @endif
                </div>
            </form>
        </div>

        <div class="md:col-span-2 bg-white p-4 shadow rounded-lg">
            <input type="text" wire:model.live="search" placeholder="নাম দিয়ে খুঁজুন..." class="w-full border rounded p-2 mb-4">

            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2 text-left">নাম</th>
                        <th class="border p-2 text-left">ঠিকানা</th>
                        <th class="border p-2 text-left">প্রধান শিক্ষক</th>
                        <th class="border p-2 text-left">প্রধান কর্মকর্তা</th>
                        <th class="border p-2 text-center">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($primary_schools as $primary_school)
                        <tr>
                            <td class="border p-2">{{ $primary_school->name }}</td>
                            <td class="border p-2">{{ $primary_school->address }}</td>
                            <td class="border p-2">{{ $primary_school->headmaster_name }}</td>
                            <td class="border p-2">{{ $primary_school->president_name }}</td>
                            <td class="border p-2 text-center">
                                <button wire:click="edit({{ $primary_school->id }})" class="text-blue-500 hover:underline">এডিট</button>
                                <button onclick="confirm('আপনি কি নিশ্চিত?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $primary_school->id }})" class="text-red-500 hover:underline ml-2">মুছুন</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $primary_schools->links() }}
            </div>
        </div>
    </div>
</div>
