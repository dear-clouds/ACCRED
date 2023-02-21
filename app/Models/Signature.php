<?php

namespace App\Models;

use File;
use Illuminate\Database\Eloquent\SoftDeletes;
use PDF;

class Signature extends MyBaseModel
{
    use SoftDeletes;

    /**
     * The account associated with the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attendees()
    {
        return $this->belongsTo(\App\Models\Attendee::class);
    }

    /**
     * The event associated with the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(\App\Models\Event::class);
    }

    /**
     * The tickets associated with the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(\App\Models\Ticket::class);
    }
}
