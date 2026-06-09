<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityDetail;
use App\Models\ActivityImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActivityDetailController extends Controller
{
    public function index()
    {
        $details = ActivityDetail::withoutGlobalScope('sort_order')
            ->with('activity', 'images')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(20);
        $activities = Activity::all();
        return view('admin.activity-details.index', compact('details', 'activities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'activity_id' => 'nullable|exists:activities,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'nullable|date',
            'slug' => 'nullable|string|max:255|unique:activity_details,slug',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $data['sort_order'] = ActivityDetail::max('sort_order') + 1;
        $detail = ActivityDetail::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('activity-details', 'public');
                $detail->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('admin.activity-details')->with('success', 'Activity detail added.');
    }

    public function update(Request $request, $id)
    {
        $detail = ActivityDetail::findOrFail($id);

        $data = $request->validate([
            'activity_id' => 'nullable|exists:activities,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'nullable|date',
            'slug' => 'nullable|string|max:255|unique:activity_details,slug,' . $id,
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $detail->update($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('activity-details', 'public');
                $detail->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('admin.activity-details')->with('success', 'Activity detail updated.');
    }

    public function reorder(Request $request)
    {
        $ids = $request->input('ids', []);
        foreach ($ids as $i => $id) {
            ActivityDetail::where('id', $id)->update(['sort_order' => $i]);
        }
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $detail = ActivityDetail::findOrFail($id);
        foreach ($detail->images as $img) {
            \Storage::disk('public')->delete($img->image_path);
            $img->delete();
        }
        $detail->delete();
        return redirect()->route('admin.activity-details')->with('success', 'Activity detail deleted.');
    }

    public function destroyImage($id)
    {
        $image = ActivityImage::findOrFail($id);
        \Storage::disk('public')->delete($image->image_path);
        $image->delete();
        return response()->json(['success' => true]);
    }
}
