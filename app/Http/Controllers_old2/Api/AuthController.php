<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller; 
use App\Models\User;
use App\Models\ClientDocuments;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use File;

class AuthController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */

     public $sp_user_id;
     public $sp_user_name;
 
     public function __construct(ClientDocuments $client_documents,User $user, Request $request, Auth $auth)
     { 
         
        $this->client_documents = $client_documents;
 
     }

    public function login(Request $request)
    { 
        $credentials = $request->only('email', 'password');

        // Add an additional condition to check user type
        $credentials['role_id'] = 3;

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            
            $token = $user->createToken('authToken')->accessToken;
            $token = $user->createToken('authToken')->plainTextToken;

            //$user->api_token = Str::random(60); // Generate a random token
            $user->api_token = $token; 
            $user->save();

            return response()->json(['token' => $user->api_token,'status' => true], 200);
        } else {
            return response()->json(['msg' => 'Wrong email or password !','status' => false], 401);
        }
    }

    public function user(Request $request)
    { 
        $user = $request->user();
        return response()->json($user);
    }

    public function upload_document(Request $request)
    {
        $user = $request->user();

        //dd($request->file('file'));

        $allowedFileTypes = ['jpg', 'jpeg', 'png'];

        if ($request->hasFile('file')) {

            $file = $request->file('file');
            $originalFileName = $file->getClientOriginalName();
            $fileExtension = strtolower($file->getClientOriginalExtension());
    
            if (in_array($fileExtension, $allowedFileTypes)) {

                $fileName = time() . '_' . str_replace(' ', '_', $originalFileName);
                //$file->storeAs('documents', $fileName, 'public');
                //$path = $request->file('file')->store('public/files');
 
                if ($file->move(public_path('documents'), $fileName)) {

                    $this->client_documents->user_id            =   $user->id;
                    $this->client_documents->file               =   $fileName;
                    $this->client_documents->created_at         =   date('Y-m-d H:i:s');

                    $this->client_documents->save();

                    return response()->json(['message' => 'File uploaded successfully'], 201);
                } else {
                    return response()->json(['message' => 'File upload failed'], 500);
                }
                return response()->json(['message' => 'File uploaded successfully'], 201);

            } else {

                return response()->json(['message' => 'Invalid file type. Allowed types: jpg, jpeg, png'], 400);
                
            }
        }

        return response()->json(['message' => 'Please select file to upload'], 400);
    }
}