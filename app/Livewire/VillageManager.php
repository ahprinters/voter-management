<?php

namespace App\Livewire;

use App\Models\Village;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Union;
use App\Models\Ward;
use Livewire\Component;
use Livewire\WithPagination;

class VillageManager extends Component
{
    use WithPagination;

    public $village_name, $division_id, $district_id, $upazila_id, $union_id, $ward_id, $village_id;
    public $districts = [], $upazilas = [], $unions = [], $wards = [];
    public $isOpen = false;

    protected $rules = [
        'village_name' => 'required|string|max:255',
        'division_id' => 'required',
        'district_id' => 'required',
        'upazila_id' => 'required',
        'union_id' => 'required',
        'ward_id' => 'required',
    ];

    // ডিপেন্ডেন্ট ড্রপডাউন লজিক
    public function updatedDivisionId($value)
    {
        $this->districts = District::where('division_id', $value)->get();
        $this->reset(['district_id', 'upazila_id', 'union_id', 'ward_id', 'upazilas', 'unions', 'wards']);
    }

    public function updatedDistrictId($value)
    {
        $this->upazilas = Upazila::where('district_id', $value)->get();
        $this->reset(['upazila_id', 'union_id', 'ward_id', 'unions', 'wards']);
    }

    public function updatedUpazilaId($value)
    {
        $this->unions = Union::where('upazila_id', $value)->get();
        $this->reset(['union_id', 'ward_id', 'wards']);
    }

    public function updatedUnionId($value)
    {
        $this->wards = Ward::where('union_id', $value)->get();
        $this->reset(['ward_id']);
    }

    public function render()
    {
        return view('livewire.village-manager', [
            'villages' => Village::with(['division', 'district', 'upazila', 'union', 'ward'])->latest()->paginate(10),
            'divisions' => Division::all(),
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal() { $this->isOpen = true; }
    public function closeModal() { $this->isOpen = false; }

    private function resetInputFields()
    {
        $this->reset(['village_name', 'division_id', 'district_id', 'upazila_id', 'union_id', 'ward_id', 'village_id', 'districts', 'upazilas', 'unions', 'wards']);
    }

    public function store()
    {
        $this->validate();

        Village::updateOrCreate(['id' => $this->village_id], [
            'village_name' => $this->village_name,
            'division_id' => $this->division_id,
            'district_id' => $this->district_id,
            'upazila_id' => $this->upazila_id,
            'union_id' => $this->union_id,
            'ward_id' => $this->ward_id,
        ]);

        session()->flash('message', $this->village_id ? 'গ্রাম সফলভাবে আপডেট হয়েছে।' : 'গ্রাম সফলভাবে তৈরি হয়েছে।');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $village = Village::findOrFail($id);
        $this->village_id = $id;
        $this->village_name = $village->village_name;
        $this->division_id = $village->division_id;

        // এডিট মোডে ড্রপডাউনগুলো লোড করা
        $this->districts = District::where('division_id', $this->division_id)->get();
        $this->district_id = $village->district_id;

        $this->upazilas = Upazila::where('district_id', $this->district_id)->get();
        $this->upazila_id = $village->upazila_id;

        $this->unions = Union::where('upazila_id', $this->upazila_id)->get();
        $this->union_id = $village->union_id;

        $this->wards = Ward::where('union_id', $this->union_id)->get();
        $this->ward_id = $village->ward_id;

        $this->openModal();
    }

    public function delete($id)
    {
        Village::find($id)->delete();
        session()->flash('message', 'গ্রাম ডিলিট করা হয়েছে।');
    }
}
