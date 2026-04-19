<?php

use Livewire\Component;
use App\Models\Voter;

new class extends Component {
    public $search = '';

    public function render()
    {
        $voters = Voter::where('name', 'like', '%' . $this->search . '%') // re %, %r %e%
            ->orWhere('father_name', 'like', '%' . $this->search . '%')
            ->orWhere('mother_name', 'like', '%' . $this->search . '%')
            ->orWhere('house_name', 'like', '%' . $this->search . '%')
            ->orWhere('voter_number', 'like', '%' . $this->search . '%')
            ->orWhere('current_location', 'like', '%' . $this->search . '%')
            ->paginate(5);

        return view('livewire.voters.voter-list', ['voters' => $voters]);
    }
};
?>

<div class="p-6">

    <input type="text" wire:model.defer="search" placeholder="Search voter..." class="border p-2 rounded w-full mb-4">

    <table class="w-full border">

        <thead class="bg-gray-200">

            <tr>
                <th class="p-2">নাম</th>
                <th class="p-2">পিতার নাম</th>
                <th class="p-2">মাতার নাম</th>
                <th class="p-2">বাড়ির নাম</th>
                <th class="p-2">ভোটার নম্বর</th>
                <th class="p-2">অবস্থান</th>
                <th class="p-2">Action</th>
            </tr>

        </thead>

        <tbody>

            @foreach ($voters as $voter)
                <tr class="border-t">

                    <td class="p-2">{{ $voter->name }}</td>
                    <td class="p-2">{{ $voter->father_name }}</td>
                    <td class="p-2">{{ $voter->mother_name }}</td>
                    <td class="p-2">{{ $voter->house_name }}</td>
                    <td class="p-2">{{ $voter->voter_number }}</td>
                    <td class="p-2">{{ $voter->current_location }}</td>

                    <td class="p-2">

                        <a href="/voter/{{ $voter->id }}/comments" class="bg-blue-500 text-white px-3 py-1 rounded">
                            Comment
                        </a>

                    </td>

                </tr>
            @endforeach

        </tbody>

    </table>

    {{ $voters->links() }}

</div>
