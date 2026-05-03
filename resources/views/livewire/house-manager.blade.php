<div class="p-6">
    {{-- সাকসেস মেসেজ --}}
    @if (session()->has('message'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4 shadow-sm border border-green-300">
            {{ session('message') }}
        </div>
    @endif

    {{-- এন্ট্রি ও এডিট ফর্ম --}}
    <div class="bg-white p-6 rounded shadow-md mb-8 border-t-4 border-blue-500">
        <h2 class="text-xl font-bold mb-4 text-gray-700">
            {{ $isEditMode ? 'বাড়ির তথ্য পরিবর্তন করুন' : 'নতুন বাড়ির তথ্য যোগ করুন' }}
        </h2>

        <form wire:submit.prevent="saveHouse">
            {{-- লোকেশন সেকশন --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                <div class="md:col-span-3 text-blue-800 font-bold border-b border-blue-200 pb-1 mb-2">
                    ঠিকানা/লোকেশন নির্বাচন করুন
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase">বিভাগ</label>
                    <select wire:model.live="division_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm p-2 outline-none focus:ring-2 focus:ring-blue-400">
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

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase">গ্রাম</label>
                    <select wire:model.live="village_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm p-2" {{ empty($villages) ? 'disabled' : '' }}>
                        <option value="">সিলেক্ট গ্রাম</option>
                        @foreach($villages as $village)
                            <option value="{{ $village->id }}">{{ $village->village_name }}</option>
                        @endforeach
                    </select>
                    @error('village_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- বাড়ির প্রধানের তথ্য --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div>
                    <label class="block text-sm font-bold text-gray-700">বাড়ির প্রধানের নাম</label>
                    <input type="text" wire:model="house_chief_name" placeholder="উদা: রহিম মিয়ার বাড়ি" class="mt-1 w-full border border-gray-300 rounded-md p-2 outline-none focus:ring-1 focus:ring-blue-500">
                    @error('house_chief_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700">মোবাইল নম্বর</label>
                    <input type="text" wire:model="mobile_no" placeholder="০১XXXXXXXXX" class="mt-1 w-full border border-gray-300 rounded-md p-2 outline-none focus:ring-1 focus:ring-blue-500">
                    @error('mobile_no') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- ডাইনামিক ঘর ও সদস্য সেকশন --}}
            <div class="mt-8 border-t-2 border-dashed border-gray-200 pt-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-blue-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        ঘরের তালিকা ও সদস্যগণ
                    </h3>
                    <button type="button" wire:click="addRoom" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700 text-sm font-bold transition">
                        + নতুন ঘর যোগ করুন
                    </button>
                </div>

                @foreach($rooms as $roomIndex => $room)
                    <div class="bg-white p-4 rounded-lg mb-6 border-2 border-gray-100 shadow-sm relative" wire:key="room-{{ $roomIndex }}">
                        <button type="button" wire:click="removeRoom({{ $roomIndex }})" class="absolute top-2 right-2 text-red-400 hover:text-red-600 transition" title="ঘর মুছুন">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 bg-gray-50 p-3 rounded">
                            <div>
                                <label class="text-xs font-bold text-gray-600 uppercase">হোল্ডিং নং <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="rooms.{{ $roomIndex }}.holding_no" placeholder="উদা: ১০১/ক" class="w-full border-gray-300 rounded mt-1 p-2 text-sm focus:ring-1 focus:ring-green-400 outline-none">
                                @error("rooms.$roomIndex.holding_no") <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-600 uppercase">ঘরের নাম/বিবরণ</label>
                                <input type="text" wire:model="rooms.{{ $roomIndex }}.room_name" placeholder="উদা: পশ্চিম ভিটা" class="w-full border-gray-300 rounded mt-1 p-2 text-sm focus:ring-1 focus:ring-green-400 outline-none">
                            </div>
                        </div>

                        {{-- মেম্বার সেকশন --}}
                        <div class="mt-4 md:ml-6 border-l-4 border-blue-100 pl-4">
                            <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                সদস্যদের তথ্য
                            </h4>
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs">
                                    <thead class="bg-gray-100">
                                        <tr class="text-left">
                                            <th class="p-2 border">নাম <span class="text-red-500">*</span></th>
                                            <th class="p-2 border w-24">লিঙ্গ</th>
                                            <th class="p-2 border">পেশা</th>
                                            <th class="p-2 border text-center">ভোটার?</th>
                                            <th class="p-2 border text-center">ছাত্র?</th>
                                            <th class="p-2 border text-center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($room['members'] as $memberIndex => $member)
                                            <tr wire:key="member-{{ $roomIndex }}-{{ $memberIndex }}" class="hover:bg-blue-50">
                                                <td class="p-1 border">
                                                    <input type="text" wire:model="rooms.{{ $roomIndex }}.members.{{ $memberIndex }}.name" class="w-full border-none p-1 focus:ring-0">
                                                    @error("rooms.$roomIndex.members.$memberIndex.name") <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                                                </td>
                                                <td class="p-1 border text-center">
                                                    <select wire:model="rooms.{{ $roomIndex }}.members.{{ $memberIndex }}.gender" class="w-full border-none p-1 focus:ring-0 bg-transparent">
                                                        <option value="Male">পুরুষ</option>
                                                        <option value="Female">মহিলা</option>
                                                        <option value="Other">অন্যান্য</option>
                                                    </select>
                                                </td>
                                                <td class="p-1 border">
                                                    <input type="text" wire:model="rooms.{{ $roomIndex }}.members.{{ $memberIndex }}.occupation" placeholder="পেশা" class="w-full border-none p-1 focus:ring-0">
                                                </td>
                                                <td class="p-1 border text-center">
                                                    <input type="checkbox" wire:model="rooms.{{ $roomIndex }}.members.{{ $memberIndex }}.is_voter" class="rounded text-blue-600">
                                                </td>
                                                <td class="p-1 border text-center">
                                                    <input type="checkbox" wire:model="rooms.{{ $roomIndex }}.members.{{ $memberIndex }}.is_student" class="rounded text-green-600">
                                                </td>
                                                <td class="p-1 border text-center">
                                                    <button type="button" wire:click="removeMember({{ $roomIndex }}, {{ $memberIndex }})" class="text-red-500 hover:text-red-700 font-bold px-2">✕</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" wire:click="addMember({{ $roomIndex }})" class="mt-3 text-blue-600 text-xs font-bold hover:underline flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                সদস্য যোগ করুন
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 pt-6 border-t flex gap-3">
                <button type="submit" class="bg-blue-700 text-white px-8 py-3 rounded-lg shadow-lg hover:bg-blue-800 transition font-bold">
                    {{ $isEditMode ? 'সব তথ্য আপডেট করুন' : 'সব তথ্য সেভ করুন' }}
                </button>
                @if($isEditMode)
                    <button type="button" wire:click="resetForm" class="bg-gray-500 text-white px-8 py-3 rounded-lg shadow hover:bg-gray-600 transition font-bold">
                        বাতিল
                    </button>
                @endif
            </div>
        </form>
    </div>

    {{-- টেবিল সেকশন --}}
    <div class="bg-white rounded shadow-md overflow-hidden">
        <div class="bg-gray-50 p-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-700">বাড়ি তালিকা</h2>
            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">মোট: {{ $houses->total() }} টি বাড়ি</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-[10px]">
                        <th class="border-b p-3">বাড়ির প্রধান</th>
                        <th class="border-b p-3">ঠিকানা (গ্রাম/ওয়ার্ড)</th>
                        <th class="border-b p-3">ইউনিয়ন/উপজেলা</th>
                        <th class="border-b p-3 text-center">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600">
                    @forelse($houses as $house)
                    <tr class="hover:bg-gray-50 border-b transition">
                        <td class="p-3">
                            <div class="font-bold text-gray-800">{{ $house->house_chief_name }}</div>
                            <div class="text-xs text-blue-600">{{ $house->mobile_no }}</div>
                        </td>
                        <td class="p-3 uppercase text-[11px]">
                            <span class="font-semibold">{{ $house->village->village_name ?? '-' }}</span><br>
                            <span class="text-gray-500">ওয়ার্ড নং: {{ $house->ward->ward_number ?? '-' }}</span>
                        </td>
                        <td class="p-3 text-[11px]">
                            {{ $house->union->union_name ?? '-' }}, {{ $house->upazila->upazila_name ?? '-' }}
                        </td>
                        <td class="p-3 text-center flex justify-center gap-3">
                            <button wire:click="loadHouseData({{ $house->id }})" title="এডিট করুন" class="p-2 bg-blue-100 text-blue-600 rounded hover:bg-blue-600 hover:text-white transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
                            <button wire:click="deleteHouse({{ $house->id }})" wire:confirm="আপনি কি নিশ্চিতভাবে এই বাড়ির সকল তথ্য (ঘর ও সদস্য সহ) ডিলিট করতে চান?" title="ডিলিট করুন" class="p-2 bg-red-100 text-red-600 rounded hover:bg-red-600 hover:text-white transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-gray-400 italic">কোনো তথ্য পাওয়া যায়নি।</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t">
            {{ $houses->links() }}
        </div>
    </div>
</div>
