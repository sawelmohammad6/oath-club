<?php

namespace App\Models;

use App\Models\Traits\HasSortOrder;
use Illuminate\Database\Eloquent\Model;

class SportsTeam extends Model
{
    use HasSortOrder;

    protected $fillable = ['team_name', 'sport_type', 'banner_image', 'description', 'sort_order'];

    public function players()
    {
        return $this->hasMany(SportPlayer::class);
    }
}
