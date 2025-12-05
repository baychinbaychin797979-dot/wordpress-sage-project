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
        'status_id' => 'integer',
        'match_time' => 'integer',
    ];

    public function home_team()
    {
        return $this->belongsTo(Team::class, 'home_team_id', 'id');
    }

    public function away_team()
    {
        return $this->belongsTo(Team::class, 'away_team_id', 'id');
    }

    // Accessors to guarantee scores are arrays of integers (or null where appropriate)
    public function getHomeScoresAttribute($value)
    {
        $arr = is_array($value) ? $value : json_decode($value, true);
        if (!is_array($arr)) return [];
        return array_map(function ($v) {
            if ($v === null) return null;
            if ($v === "-1" || $v === -1) return -1;
            if (is_numeric($v)) return (int)$v;
            return null;
        }, $arr);
    }

    public function getAwayScoresAttribute($value)
    {
        $arr = is_array($value) ? $value : json_decode($value, true);
        if (!is_array($arr)) return [];
        return array_map(function ($v) {
            if ($v === null) return null;
            if ($v === "-1" || $v === -1) return -1;
            if (is_numeric($v)) return (int)$v;
            return null;
        }, $arr);
    }
}