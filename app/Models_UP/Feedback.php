<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{

    use HasFactory;
    protected $table = 'feedbacks';
    protected $fillable = ['resident_id', 'facility_name', 'feedback_type', 'feedback', 'suggestion', 'attachment'];

    // Define relationship with Resident model
    public function resident()
    {
        return $this->belongsTo(Resident::class, 'resident_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($feedback) {
            // Generate only if not already set
            if (!$feedback->feedback_uid) {
                $feedback->feedback_uid = 'FB-' . self::generateShortId();
            }
        });
    }

    protected static function generateShortId()
    {
        // Prevent leading zero
        $firstDigit = random_int(1, 9);

        // Generate 6 random digits
        $remaining = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        return $firstDigit . $remaining;
    }
}
