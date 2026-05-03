<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow">

        @if (session()->has('message'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('message') }}</div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">গ্রাম তালিকা</h2>
            <button wire:click="create()" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">নতুন গ্রাম যোগ করুন</button>
        </div>

        <!-- Village Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-3 border">গ্রামের নাম</th>
                        <th class="p-3 border">ইউনিয়ন / ওয়ার্ড</th>
                        <th class="p-3 border">ঠিকানা</th>
                        <th class="p-3 border text-center">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($villages as $v)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $v->village_name }}</td>
                        <td class="p-3">{{ $v->union->union_name ?? '-' }} (ওয়ার্ড: {{ $v->ward->ward_name ?? '-' }})</td>
                        <td class="p-3 text-xs uppercase text-gray-500">
                            {{ $v->division->division_name }} > {{ $v->district->district_name }} > {{ $v->upazila->upazila_name }}
                        </td>
                        <td class="p-3 text-center">
                            <button wire:click="edit({{ $v->id }})" class="text-blue-500 hover:underline mr-3">এডিট</button>
                            <button wire:click="delete({{ $v->id }})" wire:confirm="আপনি কি নিশ্চিত?" class="text-red-500 hover:underline">ডিলিট</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">{{ $villages->links() }}</div>
        </div>

        <!-- Modal -->
        @if($isOpen)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded-lg w-full max-w-2xl">
                <h3 class="text-xl font-bold mb-4">{{ $village_id ? 'এডিট গ্রাম' : 'নতুন গ্রাম যোগ করুন' }}</h3>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Village Name -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium">গ্রামের নাম</label>
                        <input type="text" wire:model="village_name" class="w-full border p-2 rounded">
                    </div>

                    <!-- Division -->
                    <div>
                        <label class="block text-sm font-medium">বিভাগ</label>
                        <select wire:model.live="division_id" class="w-full border p-2 rounded">
                            <option value="">সিলেক্ট করুন</option>
                            @foreach($divisions as $div) <option value="{{ $div->id }}">{{ $div->division_name }}</option> @endforeach
                        </select>
                    </div>

                    <!-- District -->
                    <div>
                        <label class="block text-sm font-medium">জেলা</label>
                        <select wire:model.live="district_id" class="w-full border p-2 rounded" {{ empty($districts) ? 'disabled' : '' }}>
                            <option value="">সিলেক্ট করুন</option>
                            @foreach($districts as $dis) <option value="{{ $dis->id }}">{{ $dis->district_name }}</option> @endforeach
                        </select>
                    </div>

                    <!-- Upazila -->
                    <div>
                        <label class="block text-sm font-medium">উপজেলা</label>
                        <select wire:model.live="upazila_id" class="w-full border p-2 rounded" {{ empty($upazilas) ? 'disabled' : '' }}>
                            <option value="">সিলেক্ট করুন</option>
                            @foreach($upazilas as $upa) <option value="{{ $upa->id }}">{{ $upa->upazila_name }}</option> @endforeach
                        </select>
                    </div>

                    <!-- Union -->
                    <div>
                        <label class="block text-sm font-medium">ইউনিয়ন</label>
                        <select wire:model.live="union_id" class="w-full border p-2 rounded" {{ empty($unions) ? 'disabled' : '' }}>
                            <option value="">সিলেক্ট করুন</option>
                            @foreach($unions as $uni) <option value="{{ $uni->id }}">{{ $uni->union_name }}</option> @endforeach
                        </select>
                    </div>

                    <!-- Ward -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium">ওয়ার্ড</label>
                        <select wire:model="ward_id" class="w-full border p-2 rounded" {{ empty($wards) ? 'disabled' : '' }}>
                            <option value="">সিলেক্ট করুন</option>
                            @foreach($wards as $ward) <option value="{{ $ward->id }}">{{ $ward->ward_name }}</option> @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end mt-6 gap-3">
                    <button wire:click="closeModal()" class="px-4 py-2 bg-gray-300 rounded">বাতিল</button>
                    <button wire:click="store()" class="px-4 py-2 bg-indigo-600 text-white rounded">সংরক্ষণ করুন</button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
