<div class="p-6">
    {{-- সাকসেস মেসেজ --}}
    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-500 text-white rounded shadow-md">
            {{ session('message') }}
        </div>
    @endif

    {{-- নতুন ভোটার এড/আপডেট করার ফরম --}}
    <div class="bg-gray-50 p-6 rounded-xl mb-6 border border-gray-200 shadow-sm">
        <h3 class="text-lg font-bold mb-4 text-blue-700 flex items-center gap-2">
            @if($voter_id)
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                ভোটার তথ্য সংশোধন
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                নতুন ভোটার যোগ করুন
            @endif
        </h3>

        <form wire:submit.prevent="{{ $voter_id ? 'update' : 'store' }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex flex-col">
                    <input type="text" wire:model="name" placeholder="নাম" class="border p-2 rounded-lg focus:ring-2 focus:ring-blue-300 outline-none">
                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <input type="text" wire:model="father_name" placeholder="পিতার নাম" class="border p-2 rounded-lg outline-none">
                <input type="text" wire:model="mother_name" placeholder="মাতার নাম" class="border p-2 rounded-lg outline-none">
                <input type="text" wire:model="house_name" placeholder="বাড়ির নাম" class="border p-2 rounded-lg outline-none">
                <div class="flex flex-col">
                    <input type="text" wire:model="voter_number" placeholder="ভোটার নম্বর" class="border p-2 rounded-lg outline-none">
                    @error('voter_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <input type="text" wire:model="current_location" placeholder="অবস্থান" class="border p-2 rounded-lg outline-none">
            </div>

            <div class="mt-4 flex gap-2">
                @if($voter_id)
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                        আপডেট করুন
                    </button>
                    <button type="button" wire:click="resetFields" class="bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 transition">
                        বাতিল
                    </button>
                @else
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        সেভ করুন
                    </button>
                @endif
            </div>
        </form>
    </div>

    {{-- সার্চ বক্স --}}
    <div class="mb-4">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="ভোটার খুঁজুন (নাম বা নম্বর দিয়ে)..."
                class="border pl-10 pr-4 py-2 rounded-lg w-full shadow-sm focus:ring-2 focus:ring-blue-300 outline-none">
        </div>
    </div>

    {{-- ডাটা টেবিল --}}
    <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-200">
        <table class="w-full border-collapse">
            <thead class="bg-gray-50">
                <tr class="text-left text-sm font-semibold text-gray-600">
                    <th class="p-4 border-b">নাম</th>
                    <th class="p-4 border-b">পিতা/মাতা</th>
                    <th class="p-4 border-b">ভোটার নম্বর</th>
                    <th class="p-4 border-b">অবস্থান</th>
                    <th class="p-4 border-b text-center">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse ($voters as $voter)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="p-4 font-medium text-gray-800">{{ $voter->name }}</td>
                        <td class="p-4 text-gray-600">
                            <span class="font-semibold">{{ $voter->father_name }}</span> <br>
                            <span class="text-xs text-gray-400">{{ $voter->mother_name }}</span>
                        </td>
                        <td class="p-4 font-mono text-blue-600">{{ $voter->voter_number }}</td>
                        <td class="p-4 text-gray-600">{{ $voter->current_location }}</td>
                        <td class="p-4 text-center whitespace-nowrap">
                            <div class="flex justify-center items-center gap-2">
                                {{-- এডিট আইকন বাটন --}}
                                <button wire:click="edit({{ $voter->id }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-full transition" title="এডিট">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </button>

                                {{-- ডিলিট আইকন বাটন --}}
                                <button wire:click="delete({{ $voter->id }})" wire:confirm="আপনি কি নিশ্চিতভাবে এই ভোটারকে ডিলিট করতে চান?" class="p-2 text-red-600 hover:bg-red-50 rounded-full transition" title="ডিলিট">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>

                                {{-- কমেন্ট আইকন বাটন --}}
                                <a href="{{ route('voters.voter-comments.create', $voter->id) }}" wire:navigate class="p-2 text-gray-500 hover:bg-gray-100 rounded-full transition" title="মন্তব্য">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-400 italic">কোনো তথ্য পাওয়া যায়নি।</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- পেজিনেশন --}}
    <div class="mt-6">
        {{ $voters->links() }}
    </div>
</div>
