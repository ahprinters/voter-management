<?php

use Livewire\Component;
use App\Models\{Union, Upazila, District, Division};
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $union_name, $upazila_id, $district_id, $division_id, $union_id;
    public $districts = [], $upazilas = [];
    public $isEditing = false, $search = '';

    public function updatedDivisionId($value)
    {
        $this->districts = $value ? District::where('division_id', $value)->get() : [];
        $this->upazilas = [];
        $this->district_id = $this->upazila_id = null;
    }

    public function updatedDistrictId($value)
    {
        $this->upazilas = $value ? Upazila::where('district_id', $value)->get() : [];
        $this->upazila_id = null;
    }

    public function save()
    {
        $this->validate([
            'union_name' => 'required|min:3',
            'upazila_id' => 'required|exists:upazilas,id',
            'district_id' => 'required|exists:districts,id',
            'division_id' => 'required|exists:divisions,id',
        ]);

        Union::updateOrCreate(
            ['id' => $this->union_id],
            [
                'union_name' => $this->union_name,
                'upazila_id' => $this->upazila_id,
                'district_id' => $this->district_id,
                'division_id' => $this->division_id,
            ],
        );

        session()->flash('message', $this->isEditing ? 'ইউনিয়ন আপডেট করা হয়েছে!' : 'ইউনিয়ন সফলভাবে সংরক্ষিত হয়েছে!');
        $this->resetFields();
    }

    public function edit($id)
    {
        $union = Union::findOrFail($id);
        $this->union_id = $union->id;
        $this->union_name = $union->union_name;
        $this->division_id = $union->division_id;

        // এডিটের সময় ড্রপডাউন ডেটা লোড করা
        $this->districts = District::where('division_id', $union->division_id)->get();
        $this->district_id = $union->district_id;

        $this->upazilas = Upazila::where('district_id', $union->district_id)->get();
        $this->upazila_id = $union->upazila_id;

        $this->isEditing = true;
    }

    public function delete($id)
    {
        Union::find($id)->delete();
        session()->flash('message', 'ইউনিয়ন মুছে ফেলা হয়েছে!');
    }

    public function resetFields()
    {
        $this->reset(['union_name', 'upazila_id', 'district_id', 'division_id', 'isEditing', 'union_id', 'districts', 'upazilas']);
    }

    public function with()
    {
        return [
            'unions' => Union::with(['upazila', 'district', 'division'])
                ->where('union_name', 'like', "%{$this->search}%")
                ->latest()
                ->paginate(10),
            'divisions' => Division::all(),
        ];
    }
}; ?>

<div class="p-6">
    @if (session()->has('message'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4 shadow-sm">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-5 shadow rounded-lg h-fit border border-gray-100">
            <h3 class="text-lg font-bold mb-4 text-gray-700 border-b pb-2">
                {{ $isEditing ? 'তথ্য সংশোধন' : 'নতুন ইউনিয়ন যোগ করুন' }}
            </h3>

            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">ইউনিয়নের নাম</label>
                    <input type="text" wire:model="union_name" placeholder="যেমন: গজালিয়া”
                        class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 outline-none">
                    @error('union_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">বিভাগ</label>
                    <select wire:model.live="division_id" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500">
                        <option value="">বিভাগ সিলেক্ট করুন</option>
                        @foreach ($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->division_name }}</option>
                        @endforeach
                    </select>
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

                @if ($upazila_id)
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
                @endif
            </form>
        </div>

        <div class="md:col-span-2 bg-white p-5 shadow rounded-lg border border-gray-100">
            <div class="mb-4">
                <input type="text" wire:model.live="search" placeholder="খুঁজুন..."
                    class="w-full border p-2 rounded focus:border-blue-500 outline-none">
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-700 text-sm">
                            <th class="p-3 border text-left">ইউনিয়ন</th>
                            <th class="p-3 border text-left">উপজেলা</th>
                            <th class="p-3 border text-left">জেলা</th>
                            <th class="p-3 border text-center">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        @forelse ($unions as $union)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 border">{{ $union->union_name }}</td>
                                <td class="p-3 border">{{ $union->upazila->upazila_name ?? 'N/A' }}</td>
                                <td class="p-3 border">{{ $union->district->district_name ?? 'N/A' }}</td>
                                <td class="p-3 border text-center">
                                    <button wire:click="edit({{ $union->id }})" class="text-blue-500 hover:text-blue-700 mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    <button onclick="confirm('আপনি কি নিশ্চিত?') || event.stopImmediatePropagation()"
                                            wire:click="delete({{ $union->id }})" class="text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-400">কোনো তথ্য পাওয়া যায়নি।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $unions->links() }}</div>
        </div>
    </div>
</div>
