<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }
}
