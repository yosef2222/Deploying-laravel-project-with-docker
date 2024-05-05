<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use function PHPUnit\Framework\isNull;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::all();
        return response()->json($reviews, 200);
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
        if (isNull(Auth::user()->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action. Authentication is required.',
            ], 401);
        }
        try {
            $validatedReview = $request->validate([
                'dish_id' => 'required|exists:dishes,id',
                'text' => 'required|string',
                'score' => 'required|numeric|min:1|max:5',
            ]);

            $userId = Auth::user()->id;
            $dishId = $validatedReview['dish_id'];

            $existingReview = Review::where('user_id', $userId)->where('dish_id', $dishId)->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already reviewed this dish.',
                ], 422);
            }

            $review = Review::create([
                'user_id' => Auth::user()->id,
                'dish_id' => $validatedReview['dish_id'],
                'text' => $validatedReview['text'],
                'score' => $validatedReview['score'],
            ]);

            return response()->json($review, 201);
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

        $review = Review::findOrFail($id);
        return response()->json($review, 200);
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
    public function update(Request $request, $id)
    {
        try {
            $validatedReview = $request->validate([
                'dish_id' => 'required|exists:dishes,id',
                'text' => 'required|string',
                'score' => 'required|numeric|min:1|max:5',
            ]);

            $review = Review::findOrFail($id);

            if ($review->user_id !== Auth::user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access',
                ], 403);
            }

            $review->update([
                'dish_id' => $validatedReview['dish_id'],
                'text' => $validatedReview['text'],
                'score' => $validatedReview['score'],
            ]);

            return response()->json($review, 200);
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
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json(null, 204);
    }
}
