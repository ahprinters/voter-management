<div class="p-6 max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            {{ $isEditMode ? 'ভোটার তথ্য আপডেট' : 'নতুন ভোটার যুক্ত করুন' }}
        </h2>
        <a href="#" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition text-sm">
            তালিকায় ফিরে যান
        </a>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 shadow-sm">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="saveVoter" class="bg-white p-6 rounded-lg shadow-lg border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 bg-blue-50 rounded-lg">
            <div class="md:col-span-3 text-blue-800 font-bold border-b border-blue-200 pb-1 mb-2">ঠিকানা/লোকেশন নির্বাচন করুন</div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase">বিভাগ</label>
                <select wire:model.live="division_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm p-2">
                    <option value="">সিলেক্ট বিভাগ</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->id }}">{{ $division->division_name }}</option>
                    @endforeach
                </select>
                @error('division_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase">জেলা</label>
                <select wire:model.live="district_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm p-2" {{ empty($districts) ? 'disabled' : '' }}>
                    <option value="">সিলেক্ট জেলা</option>
                    @foreach($districts as $district)
                        <option value="{{ $district->id }}">{{ $district->district_name }}</option>
                    @endforeach
                </select>
                @error('district_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase">উপজেলা</label>
                <select wire:model.live="upazila_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm p-2" {{ empty($upazilas) ? 'disabled' : '' }}>
                    <option value="">সিলেক্ট উপজেলা</option>
                    @foreach($upazilas as $upazila)
                        <option value="{{ $upazila->id }}">{{ $upazila->upazila_name }}</option>
                    @endforeach
                </select>
                @error('upazila_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase">ইউনিয়ন</label>
                <select wire:model.live="union_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm p-2" {{ empty($unions) ? 'disabled' : '' }}>
                    <option value="">সিলেক্ট ইউনিয়ন</option>
                    @foreach($unions as $union)
                        <option value="{{ $union->id }}">{{ $union->union_name }}</option>
                    @endforeach
                </select>
                @error('union_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase">ওয়ার্ড</label>
                <select wire:model.live="ward_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm p-2" {{ empty($wards) ? 'disabled' : '' }}>
                    <option value="">সিলেক্ট ওয়ার্ড</option>
                    @foreach($wards as $ward)
                        <option value="{{ $ward->id }}">{{ $ward->ward_name }} ({{ $ward->ward_number }})</option>
                    @endforeach
                </select>
                @error('ward_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2 text-gray-800 font-bold border-b pb-1 mb-2">ভোটারের ব্যক্তিগত তথ্য</div>

            <div>
                <label class="block text-sm font-medium text-gray-700">নাম</label>
                <input type="text" wire:model="name" class="mt-1 p-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border" placeholder="পূর্ণ নাম">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">ভোটার নম্বর</label>
                <input type="text" wire:model="voter_number" class="mt-1 p-2 block w-full border-gray-300 rounded-md shadow-sm border" placeholder="এনআইডি বা ভোটার নম্বর">
                @error('voter_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">পিতার নাম</label>
                <input type="text" wire:model="father_name" class="mt-1 p-2 block w-full border-gray-300 rounded-md shadow-sm border" placeholder="পিতার নাম">
                @error('father_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">মাতার নাম</label>
                <input type="text" wire:model="mother_name" class="mt-1 p-2 block w-full border-gray-300 rounded-md shadow-sm border" placeholder="মাতার নাম">
                @error('mother_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">বাড়ির নাম</label>
                <input type="text" wire:model="house_name" class="mt-1 p-2 block w-full border-gray-300 rounded-md shadow-sm border" placeholder="বাড়ির নাম">
                @error('house_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">বর্তমান অবস্থান</label>
                <textarea wire:model="current_location" rows="2" class="mt-1 p-2 block w-full border-gray-300 rounded-md shadow-sm border" placeholder="গ্রাম/মহল্লা এবং বিস্তারিত ঠিকানা"></textarea>
                @error('current_location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2 mt-4">
                <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 transition font-bold shadow-md">
                    {{ $isEditMode ? 'তথ্য আপডেট করুন' : 'ভোটার সংরক্ষণ করুন' }}
                </button>
            </div>
        </div>
    </form>
</div>
