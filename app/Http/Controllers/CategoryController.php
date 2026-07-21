<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //Create Category
    public function createCategory(Request $request){
        //validation stage
        $validated=$request->validate([
            'name'=>'required|string'
        ]);

        //fill data to an empty model
        $category = new Category();
        $category->name = $validated['name'];

        //save to the database(different technology)
        try{
            $category->save();
            return response()->json([
                'message'=>'Category saved successfully',
                'category'=>$category
            ]);
        } catch(Exception $exception){
            return response()->json([
                'message'=>'Failed to save',
                'error'=>$exception->getMessage()
            ]);
        }
    }

    //Read all categories
    public function readCategories(){
        try{
            $categories = Category::all();
            return response()->json($categories);
        } catch(Exception $exception){
            return response()->json([
                'message'=>'Failed to Fetch Categories',
                'error'=>$exception->getMessage()
            ]);
        }
    }

    //Read (id) specific record
    public function readCategory($id){
        try{
            $category = Category::where('id',$id)->first();
            return response()->json($category);
        } catch(Exception $exception){
            return response()->json([
                'message'=>'Failed to Fetch Category',
                'error'=>$exception->getMessage()
            ]);
        }
    }

    //Update category(id)
    public function updateCategory($id, Request $request){
        //validation stage
        $validated=$request->validate([
            'name'=>'required|string'
        ]);

        //Fetch category(id) to fill the Category model
        $existingCategory = Category::where('id',$id)->first();
        $existingCategory->name = $validated['name'];

        //Save to the database (different technology)
        try{
            $existingCategory->save();
            return response()->json([
                'message'=>'Category updated successfully!',
                'category'=>$existingCategory
            ]);
        } catch(Exception $exception){
            return response()->json([
                'message'=>'Failed to Update Category',
                'error'=>$exception->getMessage()
            ]);
        }
    }

    //Delete category function
    public function deleteCategory($id){
        try{
            $category = Category::where('id', $id)->first();
            if($category){
                $category->delete();
                return response()->json("Category Deleted Successfully!");
            }
        } catch(Exception $exception){
            return response()->json([
                'message'=>'Failed to Delete Category',
                'error'=>$exception->getMessage()
            ]);
        }
    }
}
