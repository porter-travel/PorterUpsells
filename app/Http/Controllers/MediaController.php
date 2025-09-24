<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMediaRequest;
use App\Http\Requests\UpdateMediaRequest;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Media::orderBy('created_at', 'desc'); // newest first

        if ($request->filled('search')) {
            $search = $request->input('search');

            // Adjust the column(s) you're searching, e.g., 'title' or 'name'
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        $media = $query->paginate(30)->withQueryString(); // preserves ?search=value

        return view('admin.media.index', compact('media'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMediaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Media $media)
    {
        //
    }

    public function listAjax(Request $request, MediaService $mediaService)
    {
        $media = Media::orderBy('created_at', 'desc')
            ->paginate(30);

        $media->getCollection()->transform(function ($item) use ($mediaService) {
            $item->urls = $mediaService->getMediaUrls($item);
            return $item;
        });

        return response()->json([
            'success' => true,
            'media' => $media,
        ]);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Media $media)
    {
        return view('admin.media.edit', compact('media'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMediaRequest $request, Media $media)
    {
        $media->update($request->validated());

        return redirect()->route('media.index')->with('success', 'Media updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $media)
    {
        //
    }




    public function upload(Request $request)
    {

        $MS = new MediaService();
        $media = $MS->handleUpload($request);

        if ($media) {
            return redirect()->route('media.index')->with('success', 'Media uploaded successfully!');
        }

        return redirect()->back()->with('error', 'No file was uploaded.');
    }


    public function uploadAJAX(Request $request)
    {
        $MS = new MediaService();
        $result = $MS->handleUpload($request);

        if ($result) {
            return response()->json([
                'success' => true,
                'media' => $result['media'],
                'urls' => $result['urls'],
                'filename' => $result['filename'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No file was uploaded.',
        ], 422);
    }



}
