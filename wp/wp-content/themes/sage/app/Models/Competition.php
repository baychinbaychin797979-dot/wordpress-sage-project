<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    protected $table = 'competitions';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id','name','logo'];

    public function teams()
    {
        return $this->hasMany(Team::class, 'competition_id', 'id');
    }
}
