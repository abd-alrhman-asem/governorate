<?php

namespace App\Domain\Complaint\Models;

use App\Domain\User\Models\User;
use Database\Factories\ComplaintFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Complaint extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia ;

    protected $fillable = [
        'user_id',
        'category_id',
        'type_id',
        'destination_id',
        'title',
        'text',
        'LocationLng',
        'LocationLat',
        'LocationText',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('complaints');
    }

    protected static function newFactory()
    {
        return ComplaintFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function complaintType(): BelongsTo
    {
        return $this->belongsTo(ComplaintType::class);
    }

    public function complaintCategory(): BelongsTo
    {
        return $this->belongsTo(ComplaintType::class);
    }
}
