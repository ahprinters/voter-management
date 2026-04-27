<?php

use Livewire\Component;
use App\Models\District;
use App\Models\Division;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public $district_name;
    public $division_id;
    public $district_id; // এডিটের জন্য আইডি ট্র্যাকিং

    public $isEditing = false;
    public $search = '';

    // সার্চ করলে পেজিনেশন রিসেট হবে
    public function updatingSearch()
    {
        $this->resetPage();
    }

    protected $rules = [
        'district_name' => 'required|string|min:3',
        'division_id' => 'required|exists:divisions,id',
    ];

    public function resetFields()
    {
        $this->reset(['district_name', 'division_id', 'isEditing', 'district_id']);
    }

    public function save()
    {
        $this->validate();

        // জেলাটির নাম ওই নির্দিষ্ট বিভাগে ইউনিক কিনা তা চেক করার জন্য ম্যানুয়াল চেক বা রুলস আপডেট করতে পারেন
        District::updateOrCreate(
            ['id' => $this->district_id], // আইডি থাকলে আপডেট, না থাকলে নতুন
            [
                'district_name' => $this->district_name,
                'division_id' => $this->division_id,
            ]
        );

        session()->flash('message', $this->isEditing ? 'জেলা আপডেট করা হয়েছে!' : 'নতুন জেলা যোগ করা হয়েছে!');
        $this->resetFields();
    }

    public function edit($id)
    {
        $district = District::findOrFail($id);
        $this->district_id = $district->id;
        $this->district_name = $district->district_name;
        $this->division_id = $district->division_id;
        $this->isEditing = true;
    }

    public function delete($id)
    {
        District::find($id)->delete();
        session()->flash('message', 'জেলা মুছে ফেলা হয়েছে!');
    }

    // ভিউতে ডেটা পাঠানোর সঠিক নিয়ম
    public function with() {
        return [
            'districts' => District::with('division') // ইগার লোডিং (পারফরম্যান্সের জন্য)
                        ->where('district_name', 'like', "%{$this->search}%")
                        ->latest()
                        ->paginate(10),
            'divisions' => Division::all(), // এটি মিসিং ছিল, এখন ড্রপডাউন কাজ করবে
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

                <div>
                    <input type="text" wire:model="district_name" placeholder=" can জেলার নাম" class="w-full border rounded p-2">
                    @error('district_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <select wire:model="division_id" class="w-full border rounded p-2">
                        <option value="">বিভাগ নির্বাচন করুন</option>
                        @foreach($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->division_name }}</option>
                        @endforeach
                    </select>
                    @error('division_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

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
            <input type="text" wire:model.live="search" placeholder="জেলার নাম দিয়ে খুঁজুন..." class="w-full border rounded p-2 mb-4">

            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2 text-left">জেলার নাম</th>
                        <th class="border p-2 text-left">বিভাগ</th>
                        <th class="border p-2 text-center">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($districts as $district)
                        <tr>
                            <td class="border p-2">{{ $district->district_name }}</td>
                            <td class="border p-2">{{ $district->division->division_name ?? 'N/A' }}</td>
                            <td class="border p-2 text-center">
                                <button wire:click="edit({{ $district->id }})" class="text-blue-500 hover:underline">এডিট</button>
                                <button onclick="confirm('আপনি কি নিশ্চিত?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $district->id }})" class="text-red-500 hover:underline ml-2">মুছুন</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center p-4">কোনো তথ্য পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $districts->links() }}
            </div>
        </div>
    </div>
</div>
