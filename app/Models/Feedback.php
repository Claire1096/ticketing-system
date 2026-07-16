<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['submitted_by', 'ticket_id', 'message', 'rating'])]
class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}