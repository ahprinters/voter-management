<x-layouts::app>
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold mb-4">ভোটারের মন্তব্য যোগ করুন: {{ $voter->name }}</h1>
            <a href="{{ route('voters.voter-list') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition text-sm">
                তালিকায় ফিরে যান
            </a>
    </div>

        @livewire('voter-comment-form', ['voterId' => $voter->id])

        <hr class="my-8 border-gray-200">

        {{-- এইভাবে কল করে দেখুন, এটি সরাসরি ক্লাস ফাইলকে টার্গেট করবে --}}
@livewire('voter-comments', ['voterId' => $voter->id], key('comments-list-'.time()))    </div>
</x-layouts::app>
