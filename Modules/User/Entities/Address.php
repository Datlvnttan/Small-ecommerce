<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;
    public $table = 'addresses';

    protected $fillable = [];
    protected $casts = [];
    protected $appends = ['format_address'];
    public function getFormatAddressAttribute()
    {
        return $this->ward . ', ' . $this->district . ', ' . $this->province;
    }
}
