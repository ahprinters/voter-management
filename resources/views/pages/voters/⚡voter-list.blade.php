<?php

use Livewire\Component;
use App\Models\Voter;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $search = '';

    // সার্চ টেক্সট পরিবর্তন হলে পেজিনেশন রিসেট হবে
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function with()
    {
        $voters = Voter::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('father_name', 'like', '%' . $this->search . '%')
                        ->orWhere('mother_name', 'like', '%' . $this->search . '%')
                        ->orWhere('house_name', 'like', '%' . $this->search . '%')
                        ->orWhere('voter_number', 'like', '%' . $this->search . '%')
                        ->orWhere('current_location', 'like', '%' . $this->search . '%');
                });
            })
            ->latest() // নতুন ভোটারদের আগে দেখাবে
            ->paginate(10);

        return [
            'voters' => $voters,
        ];
    }
};
?>

<div class="p-6">
    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search"
            placeholder="ভোটার খুঁজুন (নাম, পিতা, বা ভোটার নম্বর দিয়ে)..."
            class="border p-2 rounded w-full shadow-sm focus:ring focus:border-blue-300">
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300">
            <thead class="bg-gray-100">
                <tr class="text-left">
                    <th class="p-3 border">নাম</th>
                    <th class="p-3 border">পিতার নাম</th>
                    <th class="p-3 border">মাতার নাম</th>
                    <th class="p-3 border">বাড়ির নাম</th>
                    <th class="p-3 border">ভোটার নম্বর</th>
                    <th class="p-3 border">অবস্থান</th>
                    <th class="p-3 border text-center">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($voters as $voter)
                    <tr class="border-t hover:bg-gray-50 transition">
                        <td class="p-3 border">{{ $voter->name }}</td>
                        <td class="p-3 border">{{ $voter->father_name }}</td>
                        <td class="p-3 border">{{ $voter->mother_name }}</td>
                        <td class="p-3 border">{{ $voter->house_name }}</td>
                        <td class="p-3 border font-mono">{{ $voter->voter_number }}</td>
                        <td class="p-3 border">{{ $voter->current_location }}</td>
                        <td class="p-3 border text-center">
                            <a href="{{ route('voters.voter-comments.create', $voter->id) }}" wire:navigate>
                                মন্তব্য লিখুন
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-5 text-center text-gray-500">
                            কোনো তথ্য পাওয়া যায়নি।
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $voters->links() }}
    </div>
</div>
