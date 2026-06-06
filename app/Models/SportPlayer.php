<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportPlayer extends Model
{
    protected $fillable = ['sports_team_id', 'player_name', 'position'];

    public function team()
    {
        return $this->belongsTo(SportsTeam::class);
    }
}
