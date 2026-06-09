<?php

namespace App\Models;

use App\Models\Traits\HasSortOrder;
use Illuminate\Database\Eloquent\Model;

class SportPlayer extends Model
{
    use HasSortOrder;

    protected $fillable = ['sports_team_id', 'player_name', 'position', 'sort_order'];

    public function team()
    {
        return $this->belongsTo(SportsTeam::class);
    }
}
