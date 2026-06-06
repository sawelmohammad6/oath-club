<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $images = Gallery::latest()->paginate(20);
        return view('admin.gallery.index', compact('images'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'caption' => 'nullable|string|max:255',
        ]);

        $data['image'] = $request->file('image')->store('gallery', 'public');
        Gallery::create($data);

        return redirect()->route('admin.gallery')->with('success', 'Image uploaded successfully.');
    }

    public function updateCaption(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);
        $data = $request->validate([
            'caption' => 'nullable|string|max:255',
        ]);

        $gallery->update(['caption' => $data['caption'] ?? '']);
        return redirect()->route('admin.gallery')->with('success', 'Caption updated.');
    }

    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $data = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'caption' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($gallery->image);
            $data['image'] = $request->file('image')->store('gallery', 'public');
        }

        $gallery->update($data);

        return redirect()->route('admin.gallery')->with('success', 'Image updated successfully.');
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        Storage::disk('public')->delete($gallery->image);
        $gallery->delete();
        return redirect()->route('admin.gallery')->with('success', 'Image deleted.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'));
        if (empty($ids)) {
            return redirect()->route('admin.gallery')->with('error', 'No images selected.');
        }

        $images = Gallery::whereIn('id', $ids)->get();
        foreach ($images as $img) {
            Storage::disk('public')->delete($img->image);
            $img->delete();
        }

        return redirect()->route('admin.gallery')->with('success', count($ids) . ' images deleted successfully.');
    }

    public function autoImport()
    {
        $assetsPath = public_path('assets');
        $imported = 0;
        $skipped = 0;

        for ($i = 1; $i <= 24; $i++) {
            $found = false;
            $extensions = ['jpeg', 'jpg', 'png', 'webp', 'gif'];

            foreach ($extensions as $ext) {
                $filename = "gallery-{$i}.{$ext}";
                $filePath = $assetsPath . DIRECTORY_SEPARATOR . $filename;

                if (File::exists($filePath)) {
                    $existing = Gallery::where('image', 'like', "%{$filename}")->first();
                    if ($existing) {
                        $skipped++;
                        continue;
                    }

                    $destPath = 'gallery/' . $filename;
                    $destFull = storage_path('app/public/' . $destPath);

                    $destDir = dirname($destFull);
                    if (!File::exists($destDir)) {
                        File::makeDirectory($destDir, 0755, true);
                    }

                    File::copy($filePath, $destFull);

                    Gallery::create([
                        'image' => $destPath,
                        'caption' => "Gallery Image {$i}",
                    ]);

                    $imported++;
                    $found = true;
                    break;
                }
            }
        }

        $msg = "Imported {$imported} images.";
        if ($skipped > 0) $msg .= " {$skipped} already existed.";
        if ($imported === 0 && $skipped === 0) $msg = "No gallery images found in assets folder.";

        return redirect()->route('admin.gallery')->with('success', $msg);
    }
}
