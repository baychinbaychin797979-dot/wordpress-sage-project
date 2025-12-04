<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id','name','logo'];

    public function teams()
    {
        return $this->hasMany(Team::class, 'country_id', 'id');
    }
}