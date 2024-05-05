<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dish;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isNull;

class DishController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dishes = Dish::all();
        return response()->json($dishes, 200);
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
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|gt:0',
            ]);

            $dish = Dish::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'price' => $validatedData['price'],
            ]);

            return response()->json($dish, 201);
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
        $dish = Dish::findOrFail($id);
        return response()->json($dish, 200);
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
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|gt:0',
            ]);

            $dish = Dish::findOrFail($id);

            $dish->name = $validatedData['name'];
            $dish->description = $validatedData['description'];
            $dish->price = $validatedData['price'];

            $dish->save();

            return response()->json($dish, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dish not found',
            ], 404);
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
}
