<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isNull;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::all();
        return response()->json($announcements, 200);
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
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        } elseif (isNull(Auth::user()->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action. Authentication is required.',
            ], 401);
        }
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'text' => 'required|string',
                'date' => 'required|date',
                'image' => 'nullable|string',
            ]);

            $announcement = Announcement::create([
                'title' => $validatedData['title'],
                'text' => $validatedData['text'],
                'date' => $validatedData['date'],
                'image' => $validatedData['image'],
            ]);
            return response()->json($announcement, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $announcement = Announcement::findOrFail($id);
        return response()->json($announcement, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!Auth::user()->isAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        } elseif (isNull(Auth::user()->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action. Authentication is required.',
            ], 401);
        }
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'text' => 'required|string',
                'date' => 'required|date',
                'image' => 'required|string',
            ]);

            $announcement = Announcement::findOrFail($id);

            $announcement->title = $validatedData['title'];
            $announcement->text = $validatedData['text'];
            $announcement->date = $validatedData['date'];
            $announcement->image = $validatedData['image'];

            $announcement->save();

            return response()->json($announcement, 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $announcement = Announcement::findOrFail($id);
        $announcement->delete();
    }
}
