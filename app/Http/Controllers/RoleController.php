<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Exception;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //Create Role
    public function createRole(Request $request){
        //validation stage
        $validated=$request->validate([
            'name'=>'required|string',
            'slug'=>'required|string|unique:roles,slug'
        ]);

        //fill data to an empty model
        $role = new Role();
        $role->name = $validated['name'];
        $role->slug = $validated['slug'];

        //save to the database(different technology)
        try{
            $role->save();
            return response()->json([
                'message'=>'Role saved successfully',
                'role'=>$role
            ]);
        } catch(Exception $exception){
            return response()->json([
                'message'=>'Failed to save',
                'error'=>$exception->getMessage()
            ]);
        }
    }

    //Read all roles
    public function readRoles(){
        try{
            $roles = Role::all();
            return response()->json($roles);
        } catch(Exception $exception){
            return response()->json([
                'message'=>'Failed to Fetch Roles',
                'error'=>$exception->getMessage()
            ]);
        }
    }

    //Read (id) specific record
    public function readRole($id){
        try{
            $role = Role::where('id',$id)->first();            
            return response()->json($role);
        } catch(Exception $exception){
            return response()->json([
                'message'=>'Failed to Fetch Role',
                'error'=>$exception->getMessage()
            ]);
        }
    }

    //Update role(id)
    public function updateRole($id, Request $request){
        //validation stage
        $validated=$request->validate([
            'name'=>'required|string',
            'slug'=>'required|string|unique:roles,slug'
        ]);

        //Fetch role(id) to fill the Role model
        $existingRole = Role::where('id',$id)->first();
        $existingRole->name = $validated['name'];
        $existingRole->slug = $validated['slug'];

        //Save to the database (different technology)
        try{
            $existingRole->save();
            return response()->json([
                'message'=>'Role updated successfully!',
                'role'=>$existingRole
            ]);
        } catch(Exception $exception){
            return response()->json([
                'message'=>'Failed to Update Role',
                'error'=>$exception->getMessage()
            ]);
        }
    }

    //Delete role function
    public function deleteRole($id){
        try{
            $role = Role::where('id', $id)->first();
            if($role){
                $role->delete();
                return response()->json("Role Deleted Successfully!");
            }
        } catch(Exception $exception){
            return response()->json([
                'message'=>'Failed to Delete Role',
                'error'=>$exception->getMessage()
            ]);
        }
    }
}
