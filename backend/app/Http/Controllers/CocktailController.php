<?php

namespace App\Http\Controllers;

use App\Models\Cocktail;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CocktailController extends Controller
{
    public function saveCocktail(Request $request)
    {
        try {
            DB::beginTransaction();

            // Primero guardamos o recuperamos el cóctel
            $cocktail = Cocktail::firstOrCreate(
                ['name' => $request->name],
                [
                    'image' => $request->image,
                    'category' => $request->category,
                    'glass' => $request->glass,
                    'type' => $request->type,
                    'instructions' => $request->instructions
                ]
            );

            // Procesamos los ingredientes
            if ($request->ingredients) {
                foreach ($request->ingredients as $ingredientData) {
                    $ingredient = Ingredient::firstOrCreate(
                        ['name' => $ingredientData['name']],
                        ['measure' => $ingredientData['measure'] ?? null]
                    );

                    // Asociamos el ingrediente con el cóctel
                    $cocktail->ingredients()->syncWithoutDetaching([
                        $ingredient->id => ['measure' => $ingredientData['measure'] ?? null]
                    ]);
                }
            }

            // Guardamos la relación con el usuario
            $user = Auth::user();
            $user->savedCocktails()->syncWithoutDetaching([$cocktail->id]);

            DB::commit();

            return response()->json([
                'message' => 'Cóctel guardado exitosamente',
                'cocktail' => $cocktail->load('ingredients')
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al guardar el cóctel',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUserCocktails()
    {
        $user = Auth::user();
        $cocktails = $user->savedCocktails()->with('ingredients')->get();

        return response()->json([
            'cocktails' => $cocktails
        ], 200);
    }

    public function deleteCocktail($id)
    {
        try {
            $user = Auth::user();
            $user->savedCocktails()->detach($id);

            return response()->json([
                'message' => 'Cóctel eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el cóctel',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
