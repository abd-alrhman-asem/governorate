<?php

namespace App\Domain\Complaint\Models;


use Database\Factories\ComplaintTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComplaintType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected static function newFactory()
    {
        return ComplaintTypeFactory::new();
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }
}
