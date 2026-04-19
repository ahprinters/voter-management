<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\VoterComment;
use Livewire\Attributes\On;


class VoterComments extends Component
{
    public $voterId;
    public $editingCommentId = null;
    public $editTitle = '';
    public $editCommentContent = '';
    public $editCategory = '';

    // নতুন কমেন্ট সেভ হলে লিস্ট রিফ্রেশ করার জন্য
    #[On('comment-saved')]
    public function refreshList()
    {
        // লাইভওয়্যার অটোমেটিক রেন্ডার হবে
    }

    // এডিট মোড চালু করার ফাংশন
    public function editComment($id)
    {
            $comment = VoterComment::find($id);
        if ($comment) {
            $this->editingCommentId = $id;
            $this->editTitle = $comment->title;
            $this->editCommentContent = $comment->comment;
            $this->editCategory = $comment->category;
        }
    }

    // আপডেট করার ফাংশন
    public function updateComment()
    {
        $this->validate([
            'editTitle' => 'nullable|string|max:255',
            'editCommentContent' => 'required|string',
            'editCategory' => 'nullable|string',
        ]);

        $comment = VoterComment::findOrFail($this->editingCommentId);

        $comment->update([
            'title' => $this->editTitle,
            'comment' => $this->editCommentContent,
            'category' => $this->editCategory,
        ]);

        $this->editingCommentId = null; // এডিট মোড বন্ধ

        $this->dispatch('comment-saved', 'মন্তব্যটি সফলভাবে আপডেট করা হয়েছে।');
    }

    // ডিলিট করার ফাংশন
    public function deleteComment($id)
    {
        VoterComment::findOrFail($id)->delete();
        $this->dispatch('comment-saved', 'মন্তব্যটি ডিলিট করা হয়েছে।');
    }

    public function render()
    {
        return view('livewire.voter-comments', [
            'comments' => VoterComment::where('voter_id', $this->voterId)
                            ->latest()
                            ->get()
        ]);
    }
}
