<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Voter;
use Livewire\WithPagination;

class VoterList extends Component
{
    use WithPagination;

    public $search = '';
    protected $updatesQueryString = ['search'];

    //If search update then reset page to 1
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function deleteVoter($id)
    {
        $voter = Voter::findOrFail($id);
        $voter->delete();
        session()->flash('message', 'ভোটার সফলভাবে মুছে ফেলা হয়েছে।');
    }
    public function render()
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
            ->latest()
            ->paginate(10);

        return view('livewire.voter-list', [
            'voters' => $voters,
        ]);
    }
}
