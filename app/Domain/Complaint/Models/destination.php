<?php

namespace App\Domain\Complaint\Models;
use Database\Factories\DestinationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class destination extends Model
{
    protected $fillable = ['name'];

    /** @use HasFactory<\Database\Factories\DestinationFactory> */
    use HasFactory;

    protected static function newFactory()
    {
        return DestinationFactory::new();
    }
}
