<?php

namespace App\Http\Controllers;

use App\Models\GameAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GameAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = GameAccount::latest()->paginate(10);
        return view('admin.accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric',
            'status' => 'required|in:available,sold',
            'images' => 'required',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
        ]);

        $data = $request->except(['images', 'account_credentials']);

        // Process Images
        $processedImages = [];
        if ($request->filled('images')) {
            $folders = is_array($request->input('images')) ? $request->input('images') : [$request->input('images')];
            $diskLocal = Storage::disk('local');
            $diskPublic = Storage::disk('public');

            foreach ($folders as $folder) {
                if (empty($folder)) continue;

                // Handle JSON response from FilePond if it's sent as a stringified JSON
                if (is_string($folder) && strpos($folder, '{"id":') !== false) {
                    try {
                        $json = json_decode($folder, true);
                        $folder = $json['id'] ?? $folder;
                    } catch (\Exception $e) {
                    }
                }

                // Sanitize folder ID (remove potential Debugbar HTML if present)
                if (is_string($folder) && strpos($folder, '<') !== false) {
                    $folder = explode('<', $folder)[0];
                }
                $folder = trim((string)$folder);

                $tmpFiles = $diskLocal->files('tmp/' . $folder);

                if (!empty($tmpFiles)) {
                    $tempFile = $tmpFiles[0];
                    $extension = pathinfo($tempFile, PATHINFO_EXTENSION);
                    $filename = 'account-' . uniqid() . '.' . $extension;
                    // $destinationPath = 'accounts/' . $filename; // Not needed with putFile

                    // Use putFile for memory efficiency
                    $absoluteTempPath = $diskLocal->path($tempFile);
                    if (file_exists($absoluteTempPath)) {
                        $file = new \Illuminate\Http\File($absoluteTempPath);
                        $newPath = $diskPublic->putFile('accounts', $file);

                        if ($newPath) {
                            $processedImages[] = $newPath;
                            $diskLocal->deleteDirectory('tmp/' . $folder);
                        }
                    }
                }
            }
        }

        $data['images'] = $processedImages;
        $data['image_path'] = $processedImages[0] ?? null;

        // Process Accounts (Credentials) - Filtered out because requested to remove UI, but keeping DB clean
        $data['accounts'] = [];

        GameAccount::create($data);

        return redirect()->route('admin.accounts.index')->with('success', 'Account created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GameAccount $gameAccount)
    {
        return view('admin.accounts.edit', compact('gameAccount'));
    }

    /**
     * Display the specified resource.
     */
    public function show(GameAccount $gameAccount)
    {
        return view('accounts.show', compact('gameAccount'));
    }

    /**
     * Display a listing of all available accounts for the catalog.
     */
    public function catalog(Request $request)
    {
        $query = GameAccount::where('status', 'available');
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('category', 'like', '%' . $request->search . '%');
            });
        }
        
        $accounts = $query->latest()->paginate(12);
        return view('accounts.catalog', compact('accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GameAccount $gameAccount)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric',
            'status' => 'required|in:available,sold',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
        ]);

        if (!$request->has('existing_images') && !$request->filled('images')) {
            return back()->withErrors(['images' => 'Please provide at least one image.'])->withInput();
        }

        $data = $request->except(['images', 'account_credentials', 'existing_images']);

        // Manage Existing Images
        $currentImages = $gameAccount->images ?? [];
        if ($gameAccount->image_path && !in_array($gameAccount->image_path, $currentImages)) {
            $currentImages[] = $gameAccount->image_path;
        }

        $existingToKeep = $request->input('existing_images', []);

        // Delete images that were removed
        foreach ($currentImages as $img) {
            if (!in_array($img, $existingToKeep)) {
                Storage::disk('public')->delete($img);
            }
        }
        $currentImages = $existingToKeep;

        // Process New Images from FilePond
        if ($request->filled('images')) {
            $folders = is_array($request->input('images')) ? $request->input('images') : [$request->input('images')];
            $diskLocal = Storage::disk('local');
            $diskPublic = Storage::disk('public');

            foreach ($folders as $folder) {
                if (empty($folder)) continue;

                // Handle JSON response from FilePond
                if (is_string($folder) && strpos($folder, '{"id":') !== false) {
                    try {
                        $json = json_decode($folder, true);
                        $folder = $json['id'] ?? $folder;
                    } catch (\Exception $e) {
                    }
                }

                // Sanitize folder ID (remove potential Debugbar HTML)
                if (is_string($folder) && strpos($folder, '<') !== false) {
                    $folder = explode('<', $folder)[0];
                }
                $folder = trim((string)$folder);

                $tmpFiles = $diskLocal->files('tmp/' . $folder);

                if (!empty($tmpFiles)) {
                    $tempFile = $tmpFiles[0];
                    $extension = pathinfo($tempFile, PATHINFO_EXTENSION);
                    $filename = 'account-' . uniqid() . '.' . $extension;

                    // Use putFile for memory efficiency
                    $absoluteTempPath = $diskLocal->path($tempFile);
                    if (file_exists($absoluteTempPath)) {
                        $file = new \Illuminate\Http\File($absoluteTempPath);
                        $newPath = $diskPublic->putFile('accounts', $file);

                        if ($newPath) {
                            $currentImages[] = $newPath;
                            $diskLocal->deleteDirectory('tmp/' . $folder);
                        }
                    }
                }
            }
        }

        $data['images'] = $currentImages;

        // Prioritize the chosen main image from request
        if ($request->filled('main_image_path') && in_array($request->main_image_path, $currentImages)) {
            $data['image_path'] = $request->main_image_path;
        } else {
            $data['image_path'] = $currentImages[0] ?? null;
        }

        // Reset accounts as UI was removed
        $data['accounts'] = [];

        $gameAccount->update($data);

        return redirect()->route('admin.accounts.index')->with('success', 'Account updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GameAccount $gameAccount)
    {
        // Delete all images
        $images = $gameAccount->images ?? [];
        if ($gameAccount->image_path && !in_array($gameAccount->image_path, $images)) {
            $images[] = $gameAccount->image_path;
        }

        foreach ($images as $img) {
            Storage::disk('public')->delete($img);
        }

        $gameAccount->delete();

        return redirect()->route('admin.accounts.index')->with('success', 'Account deleted successfully.');
    }
}
