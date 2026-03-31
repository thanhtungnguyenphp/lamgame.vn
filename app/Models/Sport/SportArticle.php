<?php
namespace App\Models\Sport;
use Illuminate\Database\Eloquent\Model;

class SportArticle extends Model
{
    protected $fillable = ['title', 'summary', 'content', 'image_url', 'type', 'sport_id', 'read_time_minutes', 'related_matches'];
    protected $casts = ['related_matches' => 'array'];

    public function sport() { return $this->belongsTo(Sport::class); }
}
