<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Voter;
use Livewire\WithPagination;

class VoterManager extends Component
{
    use WithPagination;

    // পাবলিক প্রপার্টিসমূহ
    public $search = '';
    public $name, $father_name, $mother_name, $house_name, $voter_number, $current_location;
    public $voter_id; // আপডেট করার সময় আইডির জন্য
    public $isOpen = false; // মডাল বা ফরম ওপেন/ক্লোজ করার জন্য

    // সার্চ টেক্সট পরিবর্তন হলে পেজিনেশন রিসেট হবে
    public function updatingSearch()
    {
        $this->resetPage();
    }

// ইনপুট ফিল্ডগুলো খালি করার জন্য
public function resetFields()
{
    $this->name = '';
    $this->father_name = '';
    $this->mother_name = '';
    $this->house_name = '';
    $this->voter_number = '';
    $this->current_location = '';
    $this->voter_id = null;
}

    // নতুন ভোটার সেভ করার ফাংশন
    public function store()
    {
        // ডাটা ভ্যালিডেশন
        $this->validate([
            'name' => 'required|string|max:255',
            'voter_number' => 'required|unique:voters,voter_number',
            'current_location' => 'required',
        ]);

        // ডাটাবেজে সেভ
        Voter::create([
            'name' => $this->name,
            'father_name' => $this->father_name,
            'mother_name' => $this->mother_name,
            'house_name' => $this->house_name,
            'voter_number' => $this->voter_number,
            'current_location' => $this->current_location,
        ]);

        session()->flash('message', 'নতুন ভোটার সফলভাবে যুক্ত করা হয়েছে!');

        $this->resetFields(); // ফরম খালি করা
    }

    // এডিট করার জন্য ডেটা লোড করা
public function edit($id)
{
    $voter = Voter::findOrFail($id);

    $this->voter_id = $id; // আইডি ধরে রাখা
    $this->name = $voter->name;
    $this->father_name = $voter->father_name;
    $this->mother_name = $voter->mother_name;
    $this->house_name = $voter->house_name;
    $this->voter_number = $voter->voter_number;
    $this->current_location = $voter->current_location;

    // পেজ স্ক্রল করে উপরে ফরমের কাছে নিয়ে যাওয়ার জন্য (Optional)
    $this->dispatch('scroll-to-top');
}

    // ডেটা আপডেট করার ফাংশন
    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'voter_number' => 'required|unique:voters,voter_number,' . $this->voter_id,
            'current_location' => 'required',
        ]);

        $voter = Voter::find($this->voter_id);
        $voter->update([
            'name' => $this->name,
            'father_name' => $this->father_name,
            'mother_name' => $this->mother_name,
            'house_name' => $this->house_name,
            'voter_number' => $this->voter_number,
            'current_location' => $this->current_location,
        ]);

        session()->flash('message', 'ভোটার তথ্য সফলভাবে আপডেট করা হয়েছে!');
        $this->resetFields();
    }
    // ভোটার ডিলিট করার ফাংশন
public function delete($id)
    {
        try {
            Voter::findOrFail($id)->delete();
            session()->flash('message', 'ভোটার সফলভাবে ডিলিট করা হয়েছে।');
        } catch (\Exception $e) {
            session()->flash('error', 'কিছু একটা ভুল হয়েছে!');
        }
    }

    public function render()
    {
        $voters = Voter::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('voter_number', 'like', '%' . $this->search . '%')
                      ->orWhere('current_location', 'like', '%' . $this->search . '%')
                      ->orWhere('house_name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.voter-manager', [
            'voters' => $voters,
        ]);
    }
}
