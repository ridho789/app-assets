<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index() {
        $category = Category::orderBy('name')->get();
        return view('components.master.category', compact('category'));
    }

    public function store(Request $request) {
        $existingCategory = Category::where('name', $request->name)->first();

        if ($existingCategory) {
            return redirect()->back()->with([
                'error' => $request->name . ' already in the system',
                'error_type' => 'duplicate-alert',
                'input' => $request->all(),
            ]);

        } else {

            $name = $request->name;
            Category::insert(['name'=> $name]);
            
            return redirect()->back();
        }
    }

    public function update(Request $request) {
        $existingCategory = Category::where('id_category', $request->id)->firstOrFail();
        $currentCategory = $existingCategory->name;

        if ($currentCategory != $request->name) {
            $checkCategory = Category::where('name', $request->name)->exists();

            if ($checkCategory) {
                return redirect()->back()->with([
                    'error' => $request->name . ' already in the system',
                    'error_type' => 'duplicate-alert',
                    'input' => $request->all(),
                ]);
            }
        }

        $existingCategory->update([
            'name' => $request->name,
        ]);

        return redirect()->back();
    }
}
