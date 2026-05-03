<?php

use Livewire\Component;
use App\Models\{Village, Ward, Union, Upazila, District, Division};
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $village_id, $village_name, $ward_id, $union_id, $upazila_id, $district_id, $division_id;
    public $districts = [], $upazilas = [], $unions = [], $wards = [];
    public $isEditing = false, $search = '';

    // বিভাগ পরিবর্তন হলে জেলা লোড হবে
    public function updatedDivisionId($value)
    {
        $this->districts = $value ? District::where('division_id', $value)->get() : [];
        $this->reset(['upazilas', 'unions', 'wards', 'district_id', 'upazila_id', 'union_id', 'ward_id']);
    }

    public function updatedDistrictId($value)
    {
        $this->upazilas = $value ? Upazila::where('district_id', $value)->get() : [];
        $this->reset(['unions', 'wards', 'upazila_id', 'union_id', 'ward_id']);
    }

    public function updatedUpazilaId($value)
    {
        $this->unions = $value ? Union::where('upazila_id', $value)->get() : [];
        $this->reset(['wards', 'union_id', 'ward_id']);
    }

    public function updatedUnionId($value)
    {
        $this->wards = $value ? Ward::where('union_id', $value)->get() : [];
        $this->ward_id = null;
    }

    public function save()
    {
        $this->validate([
            'village_name' => 'required|min:2',
            'division_id'  => 'required|exists:divisions,id',
            'district_id'  => 'required|exists:districts,id',
            'upazila_id'   => 'required|exists:upazilas,id',
            'union_id'     => 'required|exists:unions,id',
            'ward_id'      => 'required|exists:wards,id',
        ]);

        Village::updateOrCreate(
            ['id' => $this->village_id],
            [
                'village_name' => $this->village_name,
                'ward_id'      => $this->ward_id,
                'union_id'     => $this->union_id,
                'upazila_id'   => $this->upazila_id,
                'district_id'  => $this->district_id,
                'division_id'  => $this->division_id,
            ]
        );

        session()->flash('message', $this->isEditing ? 'গ্রাম আপডেট করা হয়েছে!' : 'গ্রাম সফলভাবে সংরক্ষিত হয়েছে!');
        $this->resetFields();
    }

    public function edit($id)
    {
        $village = Village::findOrFail($id);
        $this->village_id   = $village->id;
        $this->village_name = $village->village_name;

        $this->division_id = $village->division_id;
        $this->districts   = District::where('division_id', $village->division_id)->get();

        $this->district_id = $village->district_id;
        $this->upazilas    = Upazila::where('district_id', $village->district_id)->get();

        $this->upazila_id  = $village->upazila_id;
        $this->unions      = Union::where('upazila_id', $village->upazila_id)->get();

        $this->union_id    = $village->union_id;
        $this->wards       = Ward::where('union_id', $village->union_id)->get();

        $this->ward_id     = $village->ward_id;
        $this->isEditing   = true;
    }

    public function delete($id)
    {
        Village::find($id)->delete();
        session()->flash('message', 'গ্রাম মুছে ফেলা হয়েছে!');
    }

    public function resetFields()
    {
        $this->reset(['village_id', 'village_name', 'ward_id', 'union_id', 'upazila_id', 'district_id', 'division_id', 'isEditing', 'districts', 'upazilas', 'unions', 'wards']);
    }

    public function with()
    {
        return [
            // সংশোধন: Ward এর বদলে Village কোয়ারি করা হয়েছে
            'villages' => Village::with(['ward', 'union', 'upazila', 'district', 'division'])
                ->where('village_name', 'like', "%{$this->search}%")
                ->latest()
                ->paginate(10),
            'divisions' => Division::all(),
        ];
    }
}; ?>

<div class="p-6">
    @if (session()->has('message'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4 shadow-sm font-medium">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Form Section -->
        <div class="bg-white p-5 shadow rounded-lg h-fit border border-gray-100">
            <h3 class="text-lg font-bold mb-4 text-gray-700 border-b pb-2">
                {{ $isEditing ? 'তথ্য সংশোধন' : 'নতুন গ্রাম যোগ করুন' }}
            </h3>

            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">গ্রামের নাম</label>
                    <input type="text" wire:model="village_name" placeholder="যেমন: শান্তিনগর"
                        class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 outline-none">
                    @error('village_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">বিভাগ</label>
                    <select wire:model.live="division_id" class="w-full border p-2 rounded">
                        <option value="">বিভাগ সিলেক্ট করুন</option>
                        @foreach ($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->division_name }}</option>
                        @endforeach
                    </select>
                    @error('division_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                @if (!empty($districts))
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">জেলা</label>
                    <select wire:model.live="district_id" class="w-full border p-2 rounded">
                        <option value="">জেলা সিলেক্ট করুন</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district->id }}">{{ $district->district_name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                @if (!empty($upazilas))
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">উপজেলা</label>
                    <select wire:model.live="upazila_id" class="w-full border p-2 rounded">
                        <option value="">উপজেলা সিলেক্ট করুন</option>
                        @foreach ($upazilas as $upazila)
                            <option value="{{ $upazila->id }}">{{ $upazila->upazila_name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                @if(!empty($unions))
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">ইউনিয়ন</label>
                    <select wire:model.live="union_id" class="w-full border p-2 rounded">
                        <option value="">ইউনিয়ন সিলেক্ট করুন</option>
                        @foreach ($unions as $union)
                            <option value="{{ $union->id }}">{{ $union->union_name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                @if(!empty($wards))
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">ওয়ার্ড</label>
                    <select wire:model="ward_id" class="w-full border p-2 rounded">
                        <option value="">ওয়ার্ড সিলেক্ট করুন</option>
                        @foreach ($wards as $ward)
                            <option value="{{ $ward->id }}">{{ $ward->ward_number }}</option>
                        @endforeach
                    </select>
                    @error('ward_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                @endif

                <div class="flex gap-2 pt-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded flex-1">
                        {{ $isEditing ? 'আপডেট' : 'সংরক্ষণ' }}
                    </button>
                    @if($isEditing)
                        <button type="button" wire:click="resetFields" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                            বাতিল
                        </button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="md:col-span-2 bg-white p-5 shadow rounded-lg border border-gray-100">
            <div class="mb-4">
                <input type="text" wire:model.live="search" placeholder="গ্রামের নাম দিয়ে খুঁজুন..."
                    class="w-full border p-2 rounded focus:border-blue-500 outline-none">
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-700 text-sm">
                            <th class="p-3 border text-left">গ্রামের নাম</th>
                            <th class="p-3 border text-left">ওয়ার্ড</th>
                            <th class="p-3 border text-left">ইউনিয়ন</th>
                            <th class="p-3 border text-left">উপজেলা</th>
                            <th class="p-3 border text-center">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm">
                        @forelse ($villages as $village)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 border font-medium">{{ $village->village_name }}</td>
                                <td class="p-3 border">{{ $village->ward->ward_number ?? 'N/A' }}</td>
                                <td class="p-3 border">{{ $village->union->union_name ?? 'N/A' }}</td>
                                <td class="p-3 border">{{ $village->upazila->upazila_name ?? 'N/A' }}</td>
                                <td class="p-3 border text-center">
                                    <button wire:click="edit({{ $village->id }})" class="text-blue-500 hover:text-blue-700 mr-2">
                                        Edit
                                    </button>
                                    <button onclick="confirm('নিশ্চিত?') || event.stopImmediatePropagation()"
                                            wire:click="delete({{ $village->id }})" class="text-red-500 hover:text-red-700">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-400">কোনো তথ্য পাওয়া যায়নি।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $villages->links() }}</div>
        </div>
    </div>
</div>
