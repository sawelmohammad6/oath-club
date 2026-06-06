<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::latest()->paginate(20);
        return view('admin.activities.index', compact('activities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('activities', 'public');
        }

        Activity::create($data);
        return redirect()->route('admin.activities')->with('success', 'Activity added.');
    }

    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($activity->image) \Storage::disk('public')->delete($activity->image);
            $data['image'] = $request->file('image')->store('activities', 'public');
        }

        $activity->update($data);
        return redirect()->route('admin.activities')->with('success', 'Activity updated.');
    }

    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        if ($activity->image) \Storage::disk('public')->delete($activity->image);
        $activity->delete();
        return redirect()->route('admin.activities')->with('success', 'Activity deleted.');
    }
}
