<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchModel extends Model
{
    protected $table = 'matches';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id','competition_id','home_team_id','away_team_id','status_id','match_time','home_scores','away_scores'];

    protected $casts = [
        'home_scores' => 'array',
        'away_scores' => 'array',
    ];

    public function home_team()
    {
        return $this->belongsTo(Team::class, 'home_team_id', 'id');
    }

    public function away_team()
    {
        return $this->belongsTo(Team::class, 'away_team_id', 'id');
    }
}
