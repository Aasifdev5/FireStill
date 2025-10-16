<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'worker_id',
        'proposed_budget',
        'message',
        'availability',
        'time_to_complete',
        'status',
    ];

    /**
     * The service request this proposal belongs to.
     */
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }

    /**
     * The worker (user) who made the proposal.
     */
    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id', 'id');
    }
}
