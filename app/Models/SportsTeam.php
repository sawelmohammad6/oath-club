<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportsTeam extends Model
{
    protected $fillable = ['team_name', 'sport_type', 'banner_image', 'description'];

    public function players()
    {
        return $this->hasMany(SportPlayer::class);
    }
}
