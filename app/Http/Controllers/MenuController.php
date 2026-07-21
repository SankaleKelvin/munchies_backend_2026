<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Exception;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    //Create Menu
    public function createMenu(Request $request){
        //validation stage
        $validated=$request->validate([
            'name'=>'required|string',
            'restaurant_id'=>'required|integer|exists:restaurants,id',
            'category_id'=>'required|integer|exists:categories,id'
        ]);

        //fill data to an empty model
        $menu = new Menu();
        $menu->name = $validated['name'];
        $menu->restaurant_id = $validated['restaurant_id'];
        $menu->category_id = $validated['category_id'];

        //save to the database(different technology)
        try{
            $menu->save();
            return response()->json([
                'message'=>'Menu saved successfully',
                'menu'=>$menu
            ]);
        } catch(Exception $exception){
            return response()->json([
                'message'=>'Failed to save',
                'error'=>$exception->getMessage()
            ]);
        }
    }

    //Read all menus
    public function readMenus(){
        try{
            // $menus = Menu::all();
            $menus = Menu::join('categories', 'menus.category_id', '=', 'categories.id')  
                            ->join('restaurants', 'menus.restaurant_id', '=', 'restaurants.id')          
                            ->select('menus.*', 'restaurants.name as restaurant_name', 'categories.name as category_name')
                            ->get();
            return response()->json($menus);
        } catch(Exception $exception){
            return response()->json([
                'message'=>'Failed to Fetch Menus',
                'error'=>$exception->getMessage()
            ]);
        }
    }

    //Read (id) specific record
    public function readMenu($id){
        try{
            // $menu = Menu::where('id',$id)->first();            
            $menu = Menu::join('categories', 'menus.category_id', '=', 'categories.id')
                            ->join('restaurants', 'menus.restaurant_id', '=', 'restaurants.id')
                            ->where('menus.id',$id)
                            ->select('menus.*', 'categories.name as category_name', 'restaurants.name as restaurant_name')
                            ->first();
            return response()->json($menu);
        } catch(Exception $exception){
            return response()->json([
                'message'=>'Failed to Fetch Menu',
                'error'=>$exception->getMessage()
            ]);
        }
    }

    //Update menu(id)
    public function updateMenu($id, Request $request){
        //validation stage
        $validated=$request->validate([
            'name'=>'required|string',
            'restaurant_id'=>'required|integer|exists:restaurants,id',
            'category_id'=>'required|integer|exists:categories,id'
        ]);

        //Fetch menu(id) to fill the Menu model
        $existingMenu = Menu::where('id',$id)->first();
        $existingMenu->name = $validated['name'];
        $existingMenu->restaurant_id = $validated['restaurant_id'];
        $existingMenu->category_id = $validated['category_id'];

        //Save to the database (different technology)
        try{
            $existingMenu->save();
            return response()->json([
                'message'=>'Menu updated successfully!',
                'menu'=>$existingMenu
            ]);
        } catch(Exception $exception){
            return response()->json([
                'message'=>'Failed to Update Menu',
                'error'=>$exception->getMessage()
            ]);
        }
    }

    //Delete menu function
    public function deleteMenu($id){
        try{
            $menu = Menu::where('id', $id)->first();
            if($menu){
                $menu->delete();
                return response()->json("Menu Deleted Successfully!");
            }
        } catch(Exception $exception){
            return response()->json([
                'message'=>'Failed to Delete Menu',
                'error'=>$exception->getMessage()
            ]);
        }
    }
}
