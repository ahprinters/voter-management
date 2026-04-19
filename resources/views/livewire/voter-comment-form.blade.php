<div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 min-h-screen">
    <form wire:submit.prevent="save" class="space-y-6">

        {{-- Success Message Alert --}}
        <div x-data="{ show: false, message: '' }"
            x-on:comment-saved.window="show = true; message = $event.detail; setTimeout(() => show = false, 5000)"
            class="fixed top-5 right-5 z-50" {{-- পজিশন ঠিক করার জন্য --}} x-transition {{-- সুন্দরভাবে আসার জন্য --}}>

            <div x-show="show"
                class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200 shadow-xl"
                style="display: none;">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span x-text="message"></span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">শিরোনাম</label>
                <input type="text" wire:model="title"
                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ক্যাটাগরি</label>
                <select wire:model="category" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 shadow-sm">
                    <option value="">নির্বাচন করুন</option>
                    <option value="complaint">অভিযোগ</option>
                    <option value="suggestion">পরামর্শ</option>
                    <option value="info">সাধারণ তথ্য</option>
                </select>
            </div>
        </div>

        <div class="col-span-2" wire:ignore>
            <label class="block text-sm font-medium text-gray-700 mb-1">বিস্তারিত বর্ণনা (CKEditor)</label>
            <div x-data="{
                initEditor() {
                    ClassicEditor
                        .create($refs.commentEditor)
                        .then(newEditor => {
                            // এডিটরটিকে গ্লোবাল উইন্ডো অবজেক্টে সেট করা হচ্ছে
                            window.voterCommentEditor = newEditor;

                            // অটো-সিঙ্ক (টাইপ করার সময়)
                            newEditor.model.document.on('change:data', () => {
                                @this.set('comment', newEditor.getData(), true);
                            });
                        })
                        .catch(error => { console.error(error); });
                }
            }" x-init="initEditor">
                <div x-ref="commentEditor" class="min-h-[200px] border rounded-lg shadow-sm">
                    {!! $comment !!}
                </div>
            </div>
            @error('comment')
                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="border-2 border-dashed border-gray-200 rounded-lg p-4 bg-gray-50">
            <label class="block text-sm font-medium text-gray-700 mb-2 font-semibold">মিডিয়া ফাইল</label>
            <input type="file" wire:model="file" class="w-full text-sm text-gray-500">

            <div wire:loading wire:target="file" class="mt-2 text-blue-600 text-xs flex items-center">
                <svg class="animate-spin h-4 w-4 mr-2 border-2 border-blue-600 border-t-transparent rounded-full"
                    viewBox="0 0 24 24"></svg>
                ফাইল আপলোড হচ্ছে...
            </div>
        </div>

        <div class="flex items-center p-3 bg-blue-50 rounded-lg border border-blue-100">
            <input type="checkbox" wire:model="is_important" id="important"
                class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            <label for="important" class="ml-3 text-sm text-gray-700 font-bold">গুরুত্বপূর্ণ তথ্য হিসেবে সেভ
                হবে?</label>
        </div>

        <div class="flex justify-end pt-4 border-t">
            <button type="submit" x-on:click="@this.set('comment', window.voterCommentEditor.getData())"
                wire:loading.attr="disabled"
                class="inline-flex items-center px-10 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300 transition-all duration-200 shadow-lg">
                <span wire:loading.remove wire:target="save">তথ্য সংরক্ষণ করুন</span>
                <span wire:loading wire:target="save" class="flex items-center">
                    <svg class="animate-spin h-5 w-5 mr-3 border-2 border-white border-t-transparent rounded-full"
                        viewBox="0 0 24 24"></svg>
                    প্রসেসিং...
                </span>
            </button>
        </div>
    </form>
</div>

<style>
    .ck-editor__editable {
        min-height: 250px !important;
    }
</style>
