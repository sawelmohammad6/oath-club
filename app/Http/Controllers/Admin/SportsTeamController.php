<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SportsTeam;
use App\Models\SportPlayer;
use Illuminate\Http\Request;

class SportsTeamController extends Controller
{
    public function index()
    {
        $teams = SportsTeam::with('players')->latest()->paginate(20);
        return view('admin.sports-teams.index', compact('teams'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'team_name' => 'required|string|max:255',
            'sport_type' => 'required|in:Cricket,Football',
            'description' => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('sports-teams', 'public');
        }

        SportsTeam::create($data);
        return redirect()->route('admin.sports-teams')->with('success', 'Team created.');
    }

    public function update(Request $request, $id)
    {
        $team = SportsTeam::findOrFail($id);

        $data = $request->validate([
            'team_name' => 'required|string|max:255',
            'sport_type' => 'required|in:Cricket,Football',
            'description' => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('banner_image')) {
            if ($team->banner_image) \Storage::disk('public')->delete($team->banner_image);
            $data['banner_image'] = $request->file('banner_image')->store('sports-teams', 'public');
        }

        $team->update($data);
        return redirect()->route('admin.sports-teams')->with('success', 'Team updated.');
    }

    public function destroy($id)
    {
        $team = SportsTeam::findOrFail($id);
        if ($team->banner_image) \Storage::disk('public')->delete($team->banner_image);
        $team->players()->delete();
        $team->delete();
        return redirect()->route('admin.sports-teams')->with('success', 'Team deleted.');
    }

    public function storePlayer(Request $request)
    {
        $data = $request->validate([
            'sports_team_id' => 'required|exists:sports_teams,id',
            'player_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
        ]);

        SportPlayer::create($data);
        return redirect()->route('admin.sports-teams')->with('success', 'Player added.');
    }

    public function updatePlayer(Request $request, $id)
    {
        $player = SportPlayer::findOrFail($id);
        $data = $request->validate([
            'player_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
        ]);
        $player->update($data);
        return redirect()->route('admin.sports-teams')->with('success', 'Player updated.');
    }

    public function destroyPlayer($id)
    {
        SportPlayer::findOrFail($id)->delete();
        return redirect()->route('admin.sports-teams')->with('success', 'Player removed.');
    }
}
