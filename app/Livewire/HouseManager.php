<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\House;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Union;
use App\Models\Ward;
use App\Models\Village;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class HouseManager extends Component
{
    use WithPagination;

    // Form fields
    public $houseId, $isEditMode = false;
    public $house_chief_name, $mobile_no;

    // লোকেশন প্রপার্টিজ
    public $division_id, $district_id, $upazila_id, $union_id, $ward_id, $village_id;

    // ডাইনামিক রুম এবং মেম্বার অ্যারে
    public $rooms = [];

    // কালেকশন প্রপার্টিজ
    public $districts = [], $upazilas = [], $unions = [], $wards = [], $villages = [];

    protected $rules = [
        'division_id' => 'required',
        'district_id' => 'required',
        'upazila_id'  => 'required',
        'union_id'    => 'required',
        'ward_id'     => 'required',
        'village_id'  => 'required',
        'house_chief_name' => 'required|string|max:255',
        'mobile_no' => 'required|string|max:15',
        // ডাইনামিক ফিল্ড ভ্যালিডেশন
        'rooms.*.holding_no' => 'required',
        'rooms.*.members.*.name' => 'required|string|max:255',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $this->loadHouseData($id);
        } else {
            // শুরুতে একটি ডিফল্ট ঘর এবং মেম্বার ফিল্ড রাখা
            $this->addRoom();
        }
    }

    // --- ডাইনামিক মেথডসমূহ ---

    public function addRoom()
    {
        $this->rooms[] = [
            'holding_no' => '',
            'room_name' => '',
            'members' => [
                ['name' => '', 'gender' => 'Male', 'is_voter' => false, 'is_student' => false, 'occupation' => '']
            ]
        ];
    }

    public function removeRoom($index)
    {
        unset($this->rooms[$index]);
        $this->rooms = array_values($this->rooms);
    }

    public function addMember($roomIndex)
    {
        $this->rooms[$roomIndex]['members'][] = [
            'name' => '', 'gender' => 'Male', 'is_voter' => false, 'is_student' => false, 'occupation' => ''
        ];
    }

    public function removeMember($roomIndex, $memberIndex)
    {
        unset($this->rooms[$roomIndex]['members'][$memberIndex]);
        $this->rooms[$roomIndex]['members'] = array_values($this->rooms[$roomIndex]['members']);
    }

    // --- ডাটা লোড ও আপডেট লজিক ---

    public function loadHouseData($id)
    {
        $house = House::with(['rooms.members'])->findOrFail($id);
        $this->houseId = $id;
        $this->isEditMode = true;

        $this->fill($house->toArray());

        // ড্রপডাউন পপুলেট
        $this->districts = District::where('division_id', $this->division_id)->get();
        $this->upazilas  = Upazila::where('district_id', $this->district_id)->get();
        $this->unions    = Union::where('upazila_id', $this->upazila_id)->get();
        $this->wards     = Ward::where('union_id', $this->union_id)->get();
        $this->villages  = Village::where('ward_id', $this->ward_id)->get();

        // রুম এবং মেম্বার ডাটা অ্যারেতে সেট করা
        $this->rooms = [];
        foreach ($house->rooms as $room) {
            $members = [];
            foreach ($room->members as $member) {
                $members[] = [
                    'name' => $member->name,
                    'gender' => $member->gender,
                    'is_voter' => (bool)$member->is_voter,
                    'is_student' => (bool)$member->is_student,
                    'occupation' => $member->occupation,
                ];
            }
            $this->rooms[] = [
                'holding_no' => $room->holding_no,
                'room_name' => $room->room_name,
                'members' => $members
            ];
        }
    }

    // --- ডিপেন্ডেন্ট ড্রপডাউন ---

    public function updatedDivisionId($value) {
        $this->districts = $value ? District::where('division_id', $value)->get() : [];
        $this->reset(['district_id', 'upazila_id', 'union_id', 'ward_id', 'village_id', 'upazilas', 'unions', 'wards', 'villages']);
    }

    public function updatedDistrictId($value) {
        $this->upazilas = $value ? Upazila::where('district_id', $value)->get() : [];
        $this->reset(['upazila_id', 'union_id', 'ward_id', 'village_id', 'unions', 'wards', 'villages']);
    }

    public function updatedUpazilaId($value) {
        $this->unions = $value ? Union::where('upazila_id', $value)->get() : [];
        $this->reset(['union_id', 'ward_id', 'village_id', 'wards', 'villages']);
    }

    public function updatedUnionId($value) {
        $this->wards = $value ? Ward::where('union_id', $value)->get() : [];
        $this->reset(['ward_id', 'village_id', 'villages']);
    }

    public function updatedWardId($value) {
        $this->villages = $value ? Village::where('ward_id', $value)->get() : [];
        $this->village_id = null;
    }

    public function saveHouse()
    {
        $this->validate();

        DB::transaction(function () {
            $payload = [
                'division_id' => $this->division_id,
                'district_id' => $this->district_id,
                'upazila_id'  => $this->upazila_id,
                'union_id'    => $this->union_id,
                'ward_id'     => $this->ward_id,
                'village_id'  => $this->village_id,
                'house_chief_name' => $this->house_chief_name,
                'mobile_no'        => $this->mobile_no,
            ];

            $house = House::updateOrCreate(['id' => $this->houseId], $payload);

            // এডিট মোড হলে আগের রুম এবং মেম্বার ডিলিট করে নতুন করে এন্ট্রি করা (সহজ সমাধান)
            if ($this->isEditMode) {
                foreach ($house->rooms as $room) {
                    $room->members()->delete();
                }
                $house->rooms()->delete();
            }

            // নতুন রুম এবং মেম্বার সেভ
            foreach ($this->rooms as $roomData) {
                $room = $house->rooms()->create([
                    'holding_no' => $roomData['holding_no'],
                    'room_name' => $roomData['room_name'],
                ]);

                foreach ($roomData['members'] as $memberData) {
                    $room->members()->create($memberData);
                }
            }
        });

        session()->flash('message', $this->isEditMode ? 'বাড়ির বিস্তারিত তথ্য আপডেট হয়েছে!' : 'বাড়ির বিস্তারিত তথ্য সংরক্ষিত হয়েছে!');

        $this->isEditMode ? redirect()->route('house-manager.create') : $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset();
        $this->isEditMode = false;
        $this->addRoom();
    }

    public function render()
    {
        return view('livewire.house-manager', [
            'houses' => House::with(['division', 'district', 'upazila', 'union', 'ward', 'village'])->latest()->paginate(10),
            'divisions' => Division::all(),
        ]);
    }
}
