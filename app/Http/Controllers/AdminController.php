<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
   
    public function createAdmin(Request $req){
       try{
            $admin=Admin::create([
                'name'=>$req->name,
                'contact_number'=>$req->contact_number,
                'email'=>$req->email,
                'password'=>$req->password,
        ]);
        return response()->json(['admin_created' => true,"id"=>$admin->id]);
       }catch(\Exception $e){
        return response()->json(['admin_created' => false, 'message' => $e->getMessage()], 500);
       }
       

        return response()->json(['admin_created'=>$admin]);
    }
    public function loginAdmin(Request $req){
        $login=Auth::guard("admin")->attempt(['email'=>$req->email,'password'=>$req->password]);
        return response()->json(['login'=>$login]);
    }
    
    public function changePasswordAdmin(Request $req){
        $admin=Admin::where('id',$req->id)->first();
        if($admin){
            $email=$admin->email;
            $login=Auth::guard("admin")->attempt(['email'=>$email,'password'=>$req->oldPassword]);
            if($login){
                if($req->oldPassword!=$req->newPassword){
                    $admin->update(["password"=>$req->newPassword]);
                    return response()->json(['changed_password'=>true]);
                }else{
                    return response()->json(['changed_password'=>false,'message'=>"Old password and new password are same"]);
                }
               
            }else{
                return response()->json(['changed_password'=>false,'message'=>"Old password didn't match"]);
            }
        }else{
            return response()->json(['changed_password'=>false,'message'=>"User not found"]);

        }
    }
}
