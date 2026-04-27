{{-- সম্পূর্ণ কোডটি এই একটি মাত্র মেইন ডিভ এর ভেতরে থাকবে --}}
<div class="mt-10">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-800 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                </path>
            </svg>
            পূর্ববর্তী মন্তব্যসমূহ ({{ count($comments) }})
        </h3>
    </div>

    <div class="space-y-4">
        @forelse($comments as $comment)
            <div wire:key="comment-item-{{ $comment->id }}"
                class="bg-white rounded-xl shadow-sm border {{ $comment->is_important ? 'border-red-200 bg-red-50/30' : 'border-gray-100' }} p-5 transition-all hover:shadow-md">

                @if ($editingCommentId == $comment->id)
                    {{-- এডিট মোড ইন্টারফেস --}}
                    <div class="space-y-4 p-4 bg-blue-50/50 rounded-xl border border-blue-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">শিরোনাম</label>
                                <input type="text" wire:model="editTitle"
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">ক্যাটাগরি</label>
                                <select wire:model="editCategory"
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 shadow-sm text-sm">
                                    <option value="">সিলেক্ট করুন</option>
                                    <option value="complaint">অভিযোগ</option>
                                    <option value="suggestion">পরামর্শ</option>
                                    <option value="info">সাধারণ তথ্য</option>
                                </select>
                            </div>
                        </div>

                        {{-- CKEditor পার্ট --}}
                        <div wire:ignore>
                            <label class="block text-xs font-medium text-gray-500 mb-1">মন্তব্য (এডিট করুন)</label>
                            <div x-data="{
                                init() {
                                    ClassicEditor
                                        .create($refs.commentEditor)
                                        .then(newEditor => {
                                            const editorInstance = newEditor;
                                            editorInstance.setData(@js($editCommentBody));
                                            editorInstance.model.document.on('change:data', () => {
                                                @this.set('editCommentBody', editorInstance.getData());
                                            });
                                        });
                                }
                            }">
                                <div x-ref="commentEditor" class="min-h-[200px] border rounded-lg shadow-sm bg-white">
                                </div>
                            </div>
                        </div>

                        {{-- আপডেট এবং বাতিল বাটন --}}
                        <div class="flex justify-end gap-2 pt-2">
                            <button wire:click="updateComment" wire:loading.attr="disabled"
                                class="px-6 py-2 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition disabled:opacity-50 shadow-md">
                                <span wire:loading.remove wire:target="updateComment">আপডেট করুন</span>
                                <span wire:loading wire:target="updateComment" class="flex items-center">
                                    <svg class="animate-spin h-3 w-3 mr-2 border-2 border-white border-t-transparent rounded-full"
                                        viewBox="0 0 24 24"></svg>
                                    আপডেট হচ্ছে...
                                </span>
                            </button>
                            <button wire:click="$set('editingCommentId', null)"
                                class="px-6 py-2 bg-gray-200 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-300 transition">
                                বাতিল
                            </button>
                        </div>
                    </div>

                    {{-- এডিট মোড ইন্টারফেস (CKEditor সহ) --}}
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">শিরোনাম</label>
                                <input type="text" wire:model="editTitle"
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">ক্যাটাগরি</label>
                                <select wire:model="editCategory"
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 shadow-sm text-sm">
                                    <option value="">সিলেক্ট করুন</option>
                                    <option value="complaint">অভিযোগ</option>
                                    <option value="suggestion">পরামর্শ</option>
                                    <option value="info">সাধারণ তথ্য</option>
                                </select>
                            </div>
                        </div>

                        {{-- CKEditor Integration --}}
                        <div wire:ignore>
                            <label class="block text-xs font-medium text-gray-500 mb-1">মন্তব্য (এডিট করুন)</label>
                            <div x-data="{
                                init() {
                                    ClassicEditor
                                        .create($refs.commentEditor)
                                        .then(newEditor => {
                                            const editorInstance = newEditor;

                                            // ডাটাবেস থেকে আসা আগের টেক্সট লোড করা
                                            editorInstance.setData(@js($editCommentBody));

                                            // ডেটা সিঙ্ক করার সঠিক লজিক (টাইপো এবং সিনট্যাক্স ফিক্সড)
                                            editorInstance.model.document.on('change:data', () => {
                                                @this.set('editCommentBody', editorInstance.getData());
                                            });
                                        })
                                        .catch(error => {
                                            console.error('CKEditor লোড হতে সমস্যা হয়েছে:', error);
                                        });
                                }
                            }">
                                <div x-ref="commentEditor" class="min-h-[200px] border rounded-lg shadow-sm text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- সাধারণ ভিউ মোড --}}
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <h4 class="font-bold text-gray-900 text-lg">
                                    {{ $comment->title ?? 'শিরোনামহীন মন্তব্য' }}</h4>
                                @if ($comment->is_important)
                                    <span
                                        class="px-2 py-0.5 text-xs font-bold bg-red-100 text-red-600 rounded-full">গুরুত্বপূর্ণ</span>
                                @endif
                                <span class="px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">
                                    {{ $comment->category == 'complaint' ? 'অভিযোগ' : ($comment->category == 'suggestion' ? 'পরামর্শ' : 'সাধারণ') }}
                                </span>
                            </div>

                            <p class="text-xs text-gray-500 mb-3 flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $comment->created_at->format('d M, Y - h:i A') }}
                                ({{ $comment->created_at->diffForHumans() }})
                            </p>

                            <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed mb-4">
                                {!! $comment->comment !!}
                            </div>
                        </div>

                        <div class="flex items-center gap-1 ml-4">
                            {{-- এডিট বাটন --}}
                            <button wire:click="editComment({{ $comment->id }})" type="button"
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="এডিট">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </button>
                            {{-- ডিলিট বাটন --}}
                            <button wire:click="deleteComment({{ $comment->id }})" wire:confirm="আপনি কি নিশ্চিত?"
                                type="button" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                title="ডিলিট">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    @if ($comment->file_path)
                        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                    </path>
                                </svg>
                                সংযুক্ত ফাইল: <span
                                    class="ml-1 font-medium">{{ strtoupper($comment->file_type) }}</span>
                            </div>
                            <a href="{{ asset('storage/' . $comment->file_path) }}" target="_blank"
                                class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg">ডাউনলোড/দেখুন</a>
                        </div>
                    @endif
                @endif
            </div>
        @empty
            <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                <p class="text-gray-500 font-medium">এই ভোটারের জন্য এখনও কোনো মন্তব্য যোগ করা হয়নি।</p>
            </div>
        @endforelse
    </div>
</div>
