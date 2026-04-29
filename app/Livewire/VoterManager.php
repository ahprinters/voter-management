<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\{Voter, Division, District, Upazila, Union, Ward};
use Illuminate\Support\Facades\DB;

class VoterManager extends Component
{
    // ভোটার প্রপার্টিজ
    public $voterId, $isEditMode = false;
    public $name, $father_name, $mother_name, $house_name, $voter_number, $current_location;

    // লোকেশন প্রপার্টিজ
    public $division_id, $district_id, $upazila_id, $union_id, $ward_id;

    // কালেকশন প্রপার্টিজ (এগুলোকে মাউন্ট বা আপডেটেড মেথডে পপুলেট করা হবে)
    public $districts = [], $upazilas = [], $unions = [], $wards = [];

    /**
     * মাউন্ট মেথড
     */
    public function mount($id = null)
    {
        if ($id) {
            $voter = Voter::findOrFail($id);
            $this->voterId = $id;
            $this->isEditMode = true;

            // তথ্য ফিল করা
            $this->fill($voter->only([
                'name', 'father_name', 'mother_name', 'house_name',
                'voter_number', 'current_location', 'division_id',
                'district_id', 'upazila_id', 'union_id', 'ward_id'
            ]));

            // চেইন ড্রপডাউন ডেটা লোড করা
            $this->districts = District::where('division_id', $this->division_id)->get();
            $this->upazilas  = Upazila::where('district_id', $this->district_id)->get();
            $this->unions    = Union::where('upazila_id', $this->upazila_id)->get();
            $this->wards     = Ward::where('union_id', $this->union_id)->get();
        }
    }

    /**
     * ড্রপডাউন চেইন লজিক (Updated Methods)
     */
    public function updatedDivisionId($value) {
        $this->districts = $value ? District::where('division_id', $value)->get() : [];
        $this->reset(['district_id', 'upazila_id', 'union_id', 'ward_id', 'upazilas', 'unions', 'wards']);
    }

    public function updatedDistrictId($value) {
        $this->upazilas = $value ? Upazila::where('district_id', $value)->get() : [];
        $this->reset(['upazila_id', 'union_id', 'ward_id', 'unions', 'wards']);
    }

    public function updatedUpazilaId($value) {
        $this->unions = $value ? Union::where('upazila_id', $value)->get() : [];
        $this->reset(['union_id', 'ward_id', 'wards']);
    }

    public function updatedUnionId($value) {
        $this->wards = $value ? Ward::where('union_id', $value)->get() : [];
        $this->reset(['ward_id']);
    }

    /**
     * ভোটার সেভ বা আপডেট করা
     */
    public function saveVoter()
    {
        $rules = [
            'name'             => 'required|string|max:255',
            'father_name'      => 'required|string|max:255',
            'mother_name'      => 'required|string|max:255',
            'house_name'       => 'required|string|max:255',
            'voter_number'     => 'required|string|max:255|unique:voters,voter_number,' . $this->voterId,
            'current_location' => 'required|string|max:255',
            'division_id'      => 'required|exists:divisions,id',
            'district_id'      => 'required|exists:districts,id',
            'upazila_id'       => 'required|exists:upazilas,id',
            'union_id'         => 'required|exists:unions,id',
            'ward_id'          => 'required|exists:wards,id',
        ];

        $validatedData = $this->validate($rules);

        try {
            DB::transaction(function () use ($validatedData) {
                if ($this->isEditMode) {
                    Voter::findOrFail($this->voterId)->update($validatedData);
                    session()->flash('message', 'ভোটার তথ্য সফলভাবে আপডেট করা হয়েছে!');
                } else {
                    Voter::create($validatedData);
                    session()->flash('message', 'ভোটার সফলভাবে যোগ করা হয়েছে!');
                }
            });

            return redirect()->route('voters.voter-list');

        } catch (\Exception $e) {
            session()->flash('error', 'কিছু একটা ভুল হয়েছে! আবার চেষ্টা করুন।');
        }
    }

    public function render()
    {
        return view('livewire.voter-manager', [
            'divisions' => Division::orderBy('name', 'asc')->get()
        ]);
    }
}
