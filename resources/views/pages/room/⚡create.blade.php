<?php

use Livewire\Component;
use App\Models\{Room, House, Village, Ward, Union, Upazila, District, Division};
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    // Form Fields
    public $room_id, $_chief_name, $mobile_no;
    public $division_id, $district_id, $upazila_id, $union_id, $ward_id, $village_id;

    // Dynamic Dropdown Lists
    public $districts = [], $upazilas = [], $unions = [], $wards = [], $villages_list = [];

    public $isEditing = false, $search = '';

    // চেইন ড্রপডাউন লজিক
    public function updatedDivisionId($value)
    {
        $this->districts = $value ? District::where('division_id', $value)->get() : [];
        $this->reset(['district_id', 'upazila_id', 'union_id', 'ward_id', 'village_id', 'upazilas', 'unions', 'wards', 'villages_list']);
    }

    public function updatedDistrictId($value)
    {
        $this->upazilas = $value ? Upazila::where('district_id', $value)->get() : [];
        $this->reset(['upazila_id', 'union_id', 'ward_id', 'village_id', 'unions', 'wards', 'villages_list']);
    }

    public function updatedUpazilaId($value)
    {
        $this->unions = $value ? Union::where('upazila_id', $value)->get() : [];
        $this->reset(['union_id', 'ward_id', 'village_id', 'wards', 'villages_list']);
    }

    public function updatedUnionId($value)
    {
        $this->wards = $value ? Ward::where('union_id', $value)->get() : [];
        $this->reset(['ward_id', 'village_id', 'villages_list']);
    }

    public function updatedWardId($value)
    {
        $this->villages_list = $value ? Village::where('ward_id', $value)->get() : [];
        $this->village_id = null;
    }

    public function save()
    {
        $this->validate([
            'house_chief_name' => 'required|min:3',
            'mobile_no'        => 'required|numeric|digits:11',
            'division_id'      => 'required',
            'district_id'      => 'required',
            'upazila_id'       => 'required',
            'union_id'         => 'required',
            'ward_id'          => 'required',
            'village_id'       => 'required',
        ]);

        House::updateOrCreate(
            ['id' => $this->house_id],
            [
                'house_chief_name' => $this->house_chief_name,
                'mobile_no'        => $this->mobile_no,
                'division_id'      => $this->division_id,
                'district_id'      => $this->district_id,
                'upazila_id'       => $this->upazila_id,
                'union_id'         => $this->union_id,
                'ward_id'          => $this->ward_id,
                'village_id'       => $this->village_id,
            ]
        );

        session()->flash('message', $this->isEditing ? 'বাড়ির তথ্য আপডেট হয়েছে!' : 'নতুন বাড়ি সফলভাবে নিবন্ধিত হয়েছে!');
        $this->resetFields();
    }

    public function edit($id)
    {
        $house = House::findOrFail($id);
        $this->house_id = $house->id;
        $this->house_chief_name = $house->house_chief_name;
        $this->mobile_no = $house->mobile_no;

        $this->division_id = $house->division_id;
        $this->districts = District::where('division_id', $house->division_id)->get();

        $this->district_id = $house->district_id;
        $this->upazilas = Upazila::where('district_id', $house->district_id)->get();

        $this->upazila_id = $house->upazila_id;
        $this->unions = Union::where('upazila_id', $house->upazila_id)->get();

        $this->union_id = $house->union_id;
        $this->wards = Ward::where('union_id', $house->union_id)->get();

        $this->ward_id = $house->ward_id;
        $this->villages_list = Village::where('ward_id', $house->ward_id)->get();

        $this->village_id = $house->village_id;
        $this->isEditing = true;
    }

    public function delete($id)
    {
        House::find($id)->delete();
        session()->flash('message', 'বাড়ির তথ্য মুছে ফেলা হয়েছে!');
    }

    public function resetFields()
    {
        $this->reset(['house_id', 'house_chief_name', 'mobile_no', 'division_id', 'district_id', 'upazila_id', 'union_id', 'ward_id', 'village_id', 'isEditing']);
    }

    public function with()
    {
        return [
            'houses' => House::with(['village', 'ward', 'union', 'upazila', 'district', 'division'])
                ->where('house_chief_name', 'like', "%{$this->search}%")
                ->orWhere('mobile_no', 'like', "%{$this->search}%")
                ->latest()
                ->paginate(10),
            'divisions' => Division::all(),
        ];
    }
}; ?>

<div class="p-6">
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 shadow-md" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Section -->
        <div class="bg-white p-6 shadow-lg rounded-xl border border-gray-200">
            <h3 class="text-xl font-bold mb-6 text-gray-800 flex items-center">
                <span class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </span>
                {{ $isEditing ? 'বাড়ির তথ্য সংশোধন' : 'নতুন বাড়ি নিবন্ধন' }}
            </h3>

            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">বাড়ির প্রধানের নাম</label>
                    <input type="text" wire:model="house_chief_name" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('house_chief_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">মোবাইল নম্বর</label>
                    <input type="text" wire:model="mobile_no" placeholder="017XXXXXXXX" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('mobile_no') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600">বিভাগ</label>
                        <select wire:model.live="division_id" class="w-full mt-1 border-gray-300 rounded-lg shadow-sm">
                            <option value="">বাছাই করুন</option>
                            @foreach ($divisions as $div) <option value="{{ $div->id }}">{{ $div->division_name }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600">জেলা</label>
                        <select wire:model.live="district_id" class="w-full mt-1 border-gray-300 rounded-lg shadow-sm" {{ empty($districts) ? 'disabled' : '' }}>
                            <option value="">বাছাই করুন</option>
                            @foreach ($districts as $dis) <option value="{{ $dis->id }}">{{ $dis->district_name }}</option> @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600">উপজেলা</label>
                        <select wire:model.live="upazila_id" class="w-full mt-1 border-gray-300 rounded-lg shadow-sm" {{ empty($upazilas) ? 'disabled' : '' }}>
                            <option value="">বাছাই করুন</option>
                            @foreach ($upazilas as $upz) <option value="{{ $upz->id }}">{{ $upz->upazila_name }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600">ইউনিয়ন</label>
                        <select wire:model.live="union_id" class="w-full mt-1 border-gray-300 rounded-lg shadow-sm" {{ empty($unions) ? 'disabled' : '' }}>
                            <option value="">বাছাই করুন</option>
                            @foreach ($unions as $uni) <option value="{{ $uni->id }}">{{ $uni->union_name }}</option> @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600">ওয়ার্ড</label>
                        <select wire:model.live="ward_id" class="w-full mt-1 border-gray-300 rounded-lg shadow-sm" {{ empty($wards) ? 'disabled' : '' }}>
                            <option value="">বাছাই করুন</option>
                            @foreach ($wards as $ward) <option value="{{ $ward->id }}">{{ $ward->ward_number }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600">গ্রাম</label>
                        <select wire:model="village_id" class="w-full mt-1 border-gray-300 rounded-lg shadow-sm" {{ empty($villages_list) ? 'disabled' : '' }}>
                            <option value="">বাছাই করুন</option>
                            @foreach ($villages_list as $vil) <option value="{{ $vil->id }}">{{ $vil->village_name }}</option> @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg transition duration-200">
                        {{ $isEditing ? 'আপডেট' : 'সংরক্ষণ' }}
                    </button>
                    @if($isEditing)
                        <button type="button" wire:click="resetFields" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                            বাতিল
                        </button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="lg:col-span-2 bg-white p-6 shadow-lg rounded-xl border border-gray-200">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-700">তালিকা</h3>
                <input type="text" wire:model.live="search" placeholder="নাম বা মোবাইল দিয়ে খুঁজুন..." class="border-gray-300 rounded-lg text-sm w-64">
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                            <th class="p-3 border-b">প্রধানের নাম</th>
                            <th class="p-3 border-b">মোবাইল</th>
                            <th class="p-3 border-b">ঠিকানা (গ্রাম, ইউনিয়ন)</th>
                            <th class="p-3 border-b text-center">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                        @forelse ($houses as $house)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3 border-b font-medium">{{ $house->house_chief_name }}</td>
                                <td class="p-3 border-b">{{ $house->mobile_no }}</td>
                                <td class="p-3 border-b">
                                    {{ $house->village->village_name ?? 'N/A' }},
                                    {{ $house->union->union_name ?? 'N/A' }}
                                </td>
                                <td class="p-3 border text-center">
                                    <button wire:click="edit({{ $house->id }})" class="text-blue-500 hover:text-blue-700 mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    <button onclick="confirm('আপনি কি নিশ্চিত?') || event.stopImmediatePropagation()"
                                            wire:click="delete({{ $house->id }})" class="text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-10 text-center text-gray-400">কোনো তথ্য পাওয়া যায়নি।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $houses->links() }}
            </div>
        </div>
    </div>
</div>
