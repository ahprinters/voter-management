<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\VoterComment;

class VoterCommentForm extends Component
{
    use WithFileUploads;

    public $voter_id;
    public $title;
    public $comment;
    public $file;
    public $category;
    public $is_important = false;

    public function mount($voterId)
    {
        $this->voter_id = $voterId;
    }

    protected $rules = [
        'title' => 'nullable|string|max:255',
        'comment' => 'required|string',
        'file' => 'nullable|file|max:10240',
        'category' => 'nullable|string|max:100',
        'is_important' => 'boolean',
    ];

    public function save()
    {
        // dd($this->all());

        $this->validate();

        $fileType = null;
        $filePath = null;

        if ($this->file) {
            $filePath = $this->file->store('voter-comments', 'public');
            $mime = $this->file->getClientMimeType();

            if (str_contains($mime, 'image')) {
                $fileType = 'image';
            } elseif (str_contains($mime, 'audio')) {
                $fileType = 'audio';
            } elseif (str_contains($mime, 'video')) {
                $fileType = 'video';
            } else {
                $fileType = 'document';
            }
        }
        VoterComment::create([
            'voter_id'     => $this->voter_id,
            'title'        => $this->title,
            'comment'      => $this->comment,
            'file_path'    => $filePath,
            'file_type'    => $fileType,
            'category'     => $this->category,
            'is_important' => $this->is_important,
        ]);

        $this->reset(['title', 'comment', 'file', 'category', 'is_important']);

        $this->dispatch('comment-saved', 'তথ্য সফলভাবে সংরক্ষণ করা হয়েছে!');
    }

    public function render()
    {
        return view('livewire.voter-comment-form');
    }
}
