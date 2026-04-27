<?php

use App\Models\Mosque;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    // Form fields
    public $mosque_id, $name, $address, $Imam_name, $Muazzin_name, $Mutawally_name, $phone_number, $comments;

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
        $this->reset(['name', 'address', 'Imam_name', 'Muazzin_name', 'Mutawally_name', 'phone_number', 'comments', 'mosque_id', 'isEditing']);
    }

    // Create or Update
    public function save() {
        $this->validate();

        Mosque::updateOrCreate(['id' => $this->mosque_id], [
            'name' => $this->name,
            'address' => $this->address,
            'Imam_name' => $this->Imam_name,
            'Muazzin_name' => $this->Muazzin_name,
            'Mutawally_name' => $this->Mutawally_name,
            'phone_number' => $this->phone_number,
            'comments' => $this->comments,
        ]);

        session()->flash('message', $this->mosque_id ? 'মসজিদের তথ্য আপডেট হয়েছে!' : 'নতুন মসজিদ যোগ করা হয়েছে!');
        $this->resetFields();
    }

    // Edit mode
    public function edit($id) {
        $mosque = Mosque::findOrFail($id);
        $this->mosque_id = $id;
        $this->name = $mosque->name;
        $this->address = $mosque->address;
        $this->Imam_name = $mosque->Imam_name;
        $this->Muazzin_name = $mosque->Muazzin_name;
        $this->Mutawally_name = $mosque->Mutawally_name;
        $this->phone_number = $mosque->phone_number;
        $this->comments = $mosque->comments;
        $this->isEditing = true;
    }

    // Delete
    public function delete($id) {
        Mosque::find($id)->delete();
        session()->flash('message', 'মসজিদের তথ্য মুছে ফেলা হয়েছে!');
    }

    // Data for the view
    public function with() {
        return [
            'mosques' => Mosque::where('name', 'like', "%{$this->search}%")
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
            <h3 class="text-lg font-bold mb-4">{{ $isEditing ? 'তথ্য সংশোধন' : 'নতুন মসজিদ যোগ করুন' }}</h3>
            <form wire:submit.prevent="save" class="space-y-3">
                <input type="text" wire:model="name" placeholder="মসজিদের নাম" class="w-full border rounded p-2">
                <input type="text" wire:model="address" placeholder="ঠিকানা" class="w-full border rounded p-2">
                <input type="text" wire:model="Imam_name" placeholder="ইমামের নাম" class="w-full border rounded p-2">
                <input type="text" wire:model="Muazzin_name" placeholder="মুয়াজ্জিনের নাম" class="w-full border rounded p-2">
                <input type="text" wire:model="Mutawally_name" placeholder="মুতাওয়াল্লীর নাম" class="w-full border rounded p-2">
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
                        <th class="border p-2 text-left">ইমাম</th>
                        <th class="border p-2 text-center">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mosques as $mosque)
                        <tr>
                            <td class="border p-2">{{ $mosque->name }}</td>
                            <td class="border p-2">{{ $mosque->address }}</td>
                            <td class="border p-2">{{ $mosque->Imam_name }}</td>
                            <td class="border p-2 text-center">
                                <button wire:click="edit({{ $mosque->id }})" class="text-blue-500 hover:underline">এডিট</button>
                                <button onclick="confirm('আপনি কি নিশ্চিত?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $mosque->id }})" class="text-red-500 hover:underline ml-2">মুছুন</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $mosques->links() }}
            </div>
        </div>
    </div>
</div>
