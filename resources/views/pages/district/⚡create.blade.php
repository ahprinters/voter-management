<?php

use Livewire\Component;
use App\Models\District;

new class extends Component
{
    public $district_name;
    public $division_id;

    public $isEditing = false;
    public $search = '';

    protected $rules = [
        'district_name' => 'required|string|min:3|unique:districts,district_name',
        'division_id' => 'required|exists:divisions,id',
    ];

    public function resetFields()
    {
        $this->reset('district_name', 'division_id');
    }

    // Create or Update
    public function save()
    {
        $this->validate();

        District::updateOrCreate([
            'district_name' => $this->district_name,
            'division_id' => $this->division_id,
        ], [
            'district_name' => $this->district_name,
            'division_id' => $this->division_id,
        ]);

        session()->flash('message', 'নতুন জেলা যোগ করা হয়েছে!');
        $this->reset('district_name', 'division_id');
    }

    //edit mode
    public function edit($id)
    {
        $district = District::findOrFail($id);
        $this->district_name = $district->district_name;
        $this->division_id = $district->division_id;
        $this->isEditing = true;
    }

    //delete
    public function delete($id)
    {
        District::find($id)->delete();
    }

    //Date for the view
     // Data for the view
    public function with() {
        return [
            'districts' => District::where('district_name', 'like', "%{$this->search}%")
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
            <h3 class="text-lg font-bold mb-4">{{ $isEditing ? 'তথ্য সংশোধন' : 'নতুন জেলা যোগ করুন' }}</h3>
            <form wire:submit.prevent="save" class="space-y-3">
                <input type="text" wire:model="district_name" placeholder="জেলার নাম" class="w-full border rounded p-2">
                <select wire:model="division_id" class="w-full border rounded p-2">
                    <option value="">বিভাগ নির্বাচন করুন</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->id }}">{{ $division->division_name }}</option>
                    @endforeach
                </select>
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
                    @foreach($districts as $district)
                        <tr>
                            <td class="border p-2">{{ $district->district_name }}</td>
                            <td class="border p-2 text-center">
                                <button wire:click="edit({{ $district->id }})" class="text-blue-500 hover:underline">এডিট</button>
                                <button onclick="confirm('আপনি কি নিশ্চিত?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $district->id }})" class="text-red-500 hover:underline ml-2">মুছুন</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $districts->links() }}
            </div>
        </div>
    </div>
</div>

