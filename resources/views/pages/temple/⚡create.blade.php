<?php

use App\Models\Temple;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    // Form fields
    public $temple_id, $name, $address, $priest_name, $president_name, $phone_number, $comments;

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
        $this->reset(['name', 'address', 'priest_name', 'president_name', 'phone_number', 'comments', 'temple_id', 'isEditing']);
    }

    // Create or Update
    public function save() {
        $this->validate();

        Temple::updateOrCreate(['id' => $this->temple_id], [
            'name' => $this->name,
            'address' => $this->address,
            'priest_name' => $this->priest_name,
            'president_name' => $this->president_name,
            'phone_number' => $this->phone_number,
            'comments' => $this->comments,
        ]);

        session()->flash('message', $this->temple_id ? 'টেম্পেলের তথ্য আপডেট হয়েছে!' : 'নতুন টেম্পেল যোগ করা হয়েছে!');
        $this->resetFields();
    }

    // Edit mode
    public function edit($id) {
        $temple = Temple::findOrFail($id);
        $this->temple_id = $id;
        $this->name = $temple->name;
        $this->address = $temple->address;
        $this->priest_name = $temple->priest_name;
        $this->president_name = $temple->president_name;
        $this->phone_number = $temple->phone_number;
        $this->comments = $temple->comments;
        $this->isEditing = true;
    }

    // Delete
    public function delete($id) {
        Temple::find($id)->delete();
        session()->flash('message', 'টেম্পেলের তথ্য মুছে ফেলা হয়েছে!');
    }

    // Data for the view
    public function with() {
        return [
            'temples' => Temple::where('name', 'like', "%{$this->search}%")
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
            <h3 class="text-lg font-bold mb-4">{{ $isEditing ? 'তথ্য সংশোধন' : 'নতুন টেম্পেল যোগ করুন' }}</h3>
            <form wire:submit.prevent="save" class="space-y-3">
                <input type="text" wire:model="name" placeholder="টেম্পেলের নাম" class="w-full border rounded p-2">
                <input type="text" wire:model="address" placeholder="ঠিকানা" class="w-full border rounded p-2">
                <input type="text" wire:model="priest_name" placeholder="পুরোহিতের নাম" class="w-full border rounded p-2">
                <input type="text" wire:model="president_name" placeholder="প্রেসিডেন্টের নাম" class="w-full border rounded p-2">
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
                        <th class="border p-2 text-left">পুরোহিতের নাম</th>
                        <th class="border p-2 text-left">প্রেসিডেন্টের নাম</th>
                        <th class="border p-2 text-center">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($temples as $temple)
                        <tr>
                            <td class="border p-2">{{ $temple->name }}</td>
                            <td class="border p-2">{{ $temple->address }}</td>
                            <td class="border p-2">{{ $temple->priest_name }}</td>
                            <td class="border p-2">{{ $temple->president_name }}</td>
                            <td class="border p-2 text-center">
                                <button wire:click="edit({{ $temple->id }})" class="text-blue-500 hover:underline">এডিট</button>
                                <button onclick="confirm('আপনি কি নিশ্চিত?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $temple->id }})" class="text-red-500 hover:underline ml-2">মুছুন</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $temples->links() }}
            </div>
        </div>
    </div>
</div>
