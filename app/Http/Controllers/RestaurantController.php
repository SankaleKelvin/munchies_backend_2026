<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Exception;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    //Create Restaurant
    public function createRestaurant(Request $request){
        //validation stage
        $validated=$request->validate([
            'name'=>'required|string',
            'description'=>'nullable|string|max:1000'
        ]);

        //fill data to an empty model
        $restaurant = new Restaurant();
        $restaurant->name = $validated['name'];
        $restaurant->description = $validated['description'];

        //save to the database(different technology)
        try{
            $restaurant->save();
            return response()->json([
                'message'=>'Restaurant saved successfully',
                'restaurant'=>$restaurant
            ]);
        } catch(Exception $exception){
            return response()->json([
                'message'=>'Failed to save',
                'error'=>$exception->getMessage()
            ]);
        }
    }

    //Read all restaurants
    public function readRestaurants(){
        try{
            $restaurants = Restaurant::all();
            return response()->json($restaurants);
        } catch(Exception $exception){
            return response()->json([
                'message'=>'Failed to Fetch Restaurants',
                'error'=>$exception->getMessage()
            ]);
        }
    }

    //Read (id) specific record
    public function readRestaurant($id){
        try{
            $restaurant = Restaurant::where('id',$id)->first();            
            return response()->json($restaurant);
        } catch(Exception $exception){
            return response()->json([
                'message'=>'Failed to Fetch Restaurant',
                'error'=>$exception->getMessage()
            ]);
        }
    }

    //Update restaurant(id)
    public function updateRestaurant($id, Request $request){
        //validation stage
        $validated=$request->validate([
            'name'=>'required|string',
            'description'=>'nullable|string|max:1000'
        ]);

        //Fetch restaurant(id) to fill the Restaurant model
        $existingRestaurant = Restaurant::where('id',$id)->first();
        $existingRestaurant->name = $validated['name'];
        $existingRestaurant->description = $request->description;

        //Save to the database (different technology)
        try{
            $existingRestaurant->save();
            return response()->json([
                'message'=>'Restaurant updated successfully!',
                'restaurant'=>$existingRestaurant
            ]);
        } catch(Exception $exception){
            return response()->json([
                'message'=>'Failed to Update Restaurant',
                'error'=>$exception->getMessage()
            ]);
        }
    }

    //Delete restaurant function
    public function deleteRestaurant($id){
        try{
            $restaurant = Restaurant::where('id', $id)->first();
            if($restaurant){
                $restaurant->delete();
                return response()->json("Restaurant Deleted Successfully!");
            }
        } catch(Exception $exception){
            return response()->json([
                'message'=>'Failed to Delete Restaurant',
                'error'=>$exception->getMessage()
            ]);
        }
    }
}
