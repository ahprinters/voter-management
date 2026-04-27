<?php

use Livewire\Component;
use App\Models\Division;

new class extends Component
{
    public $division_name;

    public $isEditing = false;
    public $search = '';

    protected $rules = [
        'division_name' => 'required|string|min:3|unique:divisions,division_name',
    ];

    public function resetFields()
    {
        $this->reset('division_name');
    }

    // Create or Update
    public function save()
    {
        $this->validate();

        Division::updateOrCreate([
            'division_name' => $this->division_name,
        ]);

        session()->flash('message', 'নতুন বিভাগ যোগ করা হয়েছে!');
        $this->reset('division_name');
    }

    //edit mode
    public function edit($id)
    {
        $division = Division::findOrFail($id);
        $this->division_name = $division->division_name;
        $this->isEditing = true;
    }

    //delete
    public function delete($id)
    {
        Division::find($id)->delete();
    }

    //Date for the view
     // Data for the view
    public function with() {
        return [
            'divisions' => Division::where('division_name', 'like', "%{$this->search}%")
                        ->latest()
                        ->paginate(10),
        ];
    }
};
?>

<div class="p-6">
    @if (session()->has('message'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-4 shadow rounded-lg h-fit">
            <h3 class="text-lg font-bold mb-4">{{ $isEditing ? 'তথ্য সংশোধন' : 'নতুন বিভাগ যোগ করুন' }}</h3>
            <form wire:submit.prevent="save" class="space-y-3">
                <input type="text" wire:model="division_name" placeholder="বিভাগের নাম" class="w-full border rounded p-2">
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
                        <th class="border p-2 text-center">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($divisions as $division)
                        <tr>
                            <td class="border p-2">{{ $division->division_name }}</td>
                            <td class="border p-2 text-center">
                                <button wire:click="edit({{ $division->id }})" class="text-blue-500 hover:underline">এডিট</button>
                                <button onclick="confirm('আপনি কি নিশ্চিত?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $division->id }})" class="text-red-500 hover:underline ml-2">মুছুন</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $divisions->links() }}
            </div>
        </div>
    </div>
</div>

