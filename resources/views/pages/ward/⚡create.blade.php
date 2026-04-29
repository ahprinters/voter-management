<?php

use Livewire\Component;
use App\Models\{Ward, Union, Upazila, District, Division};
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $ward_id, $ward_name, $ward_number, $union_id, $upazila_id, $district_id, $division_id;
    public $districts = [], $upazilas = [], $unions = [];
    public $isEditing = false, $search = '';

    public function updatedDivisionId($value)
    {
        $this->districts = $value ? District::where('division_id', $value)->get() : [];
        $this->reset(['upazilas', 'unions', 'district_id', 'upazila_id', 'union_id']);
    }

    public function updatedDistrictId($value)
    {
        $this->upazilas = $value ? Upazila::where('district_id', $value)->get() : [];
        $this->reset(['unions', 'upazila_id', 'union_id']);
    }

    public function updatedUpazilaId($value)
    {
        $this->unions = $value ? Union::where('upazila_id', $value)->get() : [];
        $this->union_id = null;
    }

    public function save()
    {
        $this->validate([
            'ward_name'   => 'required|min:2',
            'ward_number' => 'required|integer|min:1',
            'union_id'    => 'required|exists:unions,id',
            'upazila_id'  => 'required|exists:upazilas,id',
            'district_id' => 'required|exists:districts,id',
            'division_id' => 'required|exists:divisions,id',
        ]);

        Ward::updateOrCreate(
            ['id' => $this->ward_id ?? null],
            [
                'ward_name'   => $this->ward_name,
                'ward_number' => $this->ward_number,
                'union_id'    => $this->union_id,
                'upazila_id'  => $this->upazila_id,
                'district_id' => $this->district_id,
                'division_id' => $this->division_id,
            ]
        );

        session()->flash('message', $this->isEditing ? 'ওয়ার্ড আপডেট করা হয়েছে!' : 'ওয়ার্ড সফলভাবে সংরক্ষিত হয়েছে!');
        $this->resetFields();
    }

    public function edit($id)
    {
        $ward = Ward::findOrFail($id);
        $this->ward_id     = $ward->id;
        $this->ward_name   = $ward->ward_name;
        $this->ward_number = $ward->ward_number;
        $this->division_id = $ward->division_id;

        $this->districts   = District::where('division_id', $ward->division_id)->get();
        $this->district_id = $ward->district_id;

        $this->upazilas    = Upazila::where('district_id', $ward->district_id)->get();
        $this->upazila_id  = $ward->upazila_id;

        $this->unions      = Union::where('upazila_id', $ward->upazila_id)->get();
        $this->union_id    = $ward->union_id;

        $this->isEditing = true;
    }

    public function delete($id)
    {
        Ward::find($id)->delete();
        session()->flash('message', 'ওয়ার্ড মুছে ফেলা হয়েছে!');
    }

    public function resetFields()
    {
        $this->reset(['ward_name', 'ward_number', 'union_id', 'upazila_id', 'district_id', 'division_id', 'isEditing', 'ward_id', 'districts', 'upazilas', 'unions']);
    }

    public function with()
    {
        return [
            'wards' => Ward::with(['union', 'upazila', 'district', 'division'])
                ->where('ward_name', 'like', "%{$this->search}%")
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
        <div class="bg-white p-5 shadow rounded-lg h-fit border border-gray-100">
            <h3 class="text-lg font-bold mb-4 text-gray-700 border-b pb-2">
                {{ $isEditing ? 'তথ্য সংশোধন' : 'নতুন ওয়ার্ড যোগ করুন' }}
            </h3>

            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">ওয়ার্ডের নাম</label>
                    <input type="text" wire:model="ward_name" placeholder="যেমন: ১নং ওয়ার্ড"
                        class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 outline-none">
                    @error('ward_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">ওয়ার্ড নম্বর (সংখ্যা)</label>
                    <input type="number" wire:model="ward_number" placeholder="যেমন: 1"
                        class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 outline-none">
                    @error('ward_number') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">বিভাগ</label>
                    <select wire:model.live="division_id" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500">
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
                        @error('district_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
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
                        @error('upazila_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
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
                        @error('union_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                @endif

                <div class="flex gap-2 pt-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded flex-1 transition duration-200">
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

        <div class="md:col-span-2 bg-white p-5 shadow rounded-lg border border-gray-100">
            <div class="mb-4">
                <input type="text" wire:model.live="search" placeholder="ওয়ার্ডের নাম দিয়ে খুঁজুন..."
                    class="w-full border p-2 rounded focus:border-blue-500 outline-none">
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-700 text-sm">
                            <th class="p-3 border text-left">ওয়ার্ড নাম</th>
                            <th class="p-3 border text-left">ওয়ার্ড নম্বর</th>
                            <th class="p-3 border text-left">ইউনিয়ন</th>
                            <th class="p-3 border text-left">উপজেলা</th>
                            <th class="p-3 border text-left">জেলা</th>
                            <th class="p-3 border text-center">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        @forelse ($wards as $ward)
                            <tr class="hover:bg-gray-50 text-sm">
                                <td class="p-3 border font-medium">{{ $ward->ward_name }}</td>
                                <td class="p-3 border">{{ $ward->ward_number }}</td>
                                <td class="p-3 border">{{ $ward->union->union_name ?? 'N/A' }}</td>
                                <td class="p-3 border">{{ $ward->upazila->upazila_name ?? 'N/A' }}</td>
                                <td class="p-3 border">{{ $ward->district->district_name ?? 'N/A' }}</td>
                                <td class="p-3 border text-center">
                                    <button wire:click="edit({{ $ward->id }})" class="text-blue-500 hover:text-blue-700 mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    <button onclick="confirm('আপনি কি নিশ্চিত?') || event.stopImmediatePropagation()"
                                            wire:click="delete({{ $ward->id }})" class="text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-400">কোনো তথ্য পাওয়া যায়নি।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $wards->links() }}</div>
        </div>
    </div>
</div>
