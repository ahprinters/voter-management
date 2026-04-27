<?php

use Livewire\Component;
use App\Models\Upazila;
use App\Models\District;
use App\Models\Division;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public $upazila_name;
    public $district_id;
    public $division_id;
    public $upazila_id;

    public $districts_list = []; // এটি জেলাগুলোর ড্রপডাউন লিস্টের জন্য

    public $isEditing = false;
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // এটি ম্যাজিক মেথড: যখনই division_id আপডেট হবে, এটি অটো কল হবে
    public function updatedDivisionId($value)
    {
        $this->districts_list = District::where('division_id', $value)->get();
        $this->district_id = null; // বিভাগ বদলালে আগের সিলেক্ট করা জেলা মুছে যাবে
    }

    protected $rules = [
        'upazila_name' => 'required|string|min:3',
        'district_id' => 'required|exists:districts,id',
        'division_id' => 'required|exists:divisions,id',
    ];

    public function resetFields()
    {
        $this->reset(['upazila_name', 'district_id', 'division_id', 'isEditing', 'upazila_id', 'districts_list']);
    }

    public function save()
    {
        $this->validate();

        Upazila::updateOrCreate(
            ['id' => $this->upazila_id],
            [
                'upazila_name' => $this->upazila_name,
                'district_id' => $this->district_id,
                'division_id' => $this->division_id,
            ]
        );

        session()->flash('message', $this->isEditing ? 'উপজেলা আপডেট করা হয়েছে!' : 'নতুন উপজেলা যোগ করা হয়েছে!');
        $this->resetFields();
    }

    public function edit($id)
    {
        $upazila = Upazila::findOrFail($id);
        $this->upazila_id = $upazila->id;
        $this->upazila_name = $upazila->upazila_name;
        $this->division_id = $upazila->division_id;

        // এডিট করার সময় ওই বিভাগের জেলাগুলো লোড করা
        $this->districts_list = District::where('division_id', $this->division_id)->get();
        $this->district_id = $upazila->district_id;

        $this->isEditing = true;
    }

    public function delete($id)
    {
        Upazila::find($id)->delete();
        session()->flash('message', 'উপজেলা মুছে ফেলা হয়েছে!');
    }

    public function with() {
        return [
            'upazilas' => Upazila::with(['district', 'division'])
                        ->where('upazila_name', 'like', "%{$this->search}%")
                        ->latest()
                        ->paginate(10),
            'divisions' => Division::all(),
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
            <h3 class="text-lg font-bold mb-4">{{ $isEditing ? 'তথ্য সংশোধন' : 'নতুন উপজেলা যোগ করুন' }}</h3>
            <form wire:submit.prevent="save" class="space-y-3">

                <div>
                    <input type="text" wire:model="upazila_name" placeholder="উপজেলার নাম" class="w-full border rounded p-2">
                    @error('upazila_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- বিভাগ সিলেক্ট --}}
                <div>
                    <select wire:model.live="division_id" class="w-full border rounded p-2">
                        <option value="">বিভাগ নির্বাচন করুন</option>
                        @foreach($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->division_name }}</option>
                        @endforeach
                    </select>
                    @error('division_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- জেলা সিলেক্ট (বিভাগ সিলেক্ট করা থাকলে তবেই আসবে) --}}
                @if(!empty($districts_list))
                <div>
                    <select wire:model="district_id" class="w-full border rounded p-2">
                        <option value="">জেলা নির্বাচন করুন</option>
                        @foreach($districts_list as $district)
                            <option value="{{ $district->id }}">{{ $district->district_name }}</option>
                        @endforeach
                    </select>
                    @error('district_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                @endif

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                        {{ $isEditing ? 'সংরক্ষণ' : 'সংরক্ষণ' }}
                    </button>
                    @if($isEditing)
                        <button type="button" wire:click="resetFields" class="bg-gray-400 text-white px-4 py-2 rounded">বাতিল</button>
                    @endif
                </div>
            </form>
        </div>

        <div class="md:col-span-2 bg-white p-4 shadow rounded-lg">
            <input type="text" wire:model.live="search" placeholder="উপজেলার নাম দিয়ে খুঁজুন..." class="w-full border rounded p-2 mb-4">

            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2 text-left">উপজেলার নাম</th>
                        <th class="border p-2 text-left">বিভাগ</th>
                        <th class="border p-2 text-left">জেলা</th>
                        <th class="border p-2 text-center">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($upazilas as $upazila)
                        <tr>
                            <td class="border p-2">{{ $upazila->upazila_name }}</td>
                            <td class="border p-2">{{ $upazila->division->division_name ?? 'N/A' }}</td>
                            <td class="border p-2">{{ $upazila->district->district_name ?? 'N/A' }}</td>
                            <td class="border p-2 text-center">
                                <button wire:click="edit({{ $upazila->id }})" class="text-blue-500 hover:underline">এডিট</button>
                                <button onclick="confirm('আপনি কি নিশ্চিত?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $upazila->id }})" class="text-red-500 hover:underline ml-2">মুছুন</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center p-4">কোনো তথ্য পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $upazilas->links() }}
            </div>
        </div>
    </div>
</div>
