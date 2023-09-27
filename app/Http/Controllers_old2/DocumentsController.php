<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Simcard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Log;

class DocumentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

     public $sp_user_id;
     public $sp_user_name;
 
     public function __construct(Simcard $simcard,User $user, Request $request, Auth $auth)
     { 
        $this->middleware('auth');
        $this->simcard = $simcard;

        $this->middleware(function ($request, $next) {

        $this->sp_user_id = Auth::user()->id;
        $this->sp_user_name = Auth::user()->first_name.' '.Auth::user()->last_name;
        $this->menu_name = 'Simcard';
            
        return $next($request);
        });
 
        $curent_uri = $request->path();
        
        $curent_uri_arr = explode('/',$curent_uri);
        
        $curent_route = end($curent_uri_arr);

        $this->curent_route = $curent_route;

     }

    /**
     * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        
    $simcards = Simcard::orderBy('id', 'desc')->paginate(10);

    return view('backend.documents.index', [
        'title' => 'Document',
        'data' => $simcards
    ]);
     
    }

    public function client()
    {
        
    $simcards = Simcard::orderBy('id', 'desc')->paginate(10);

    return view('backend.documents.client', [
        'title' => 'Document',
        'data' => $simcards
    ]);
    
    }

    public function client_view()
    {
      
    $simcards = Simcard::orderBy('id', 'desc')->paginate(10);
   
    return view('backend.documents.client_view', [  
        'title' => 'Document',
        'data' => $simcards
    ]);
   
    
    }
    public function invoice_details()
    {
        
    $simcards = Simcard::orderBy('id', 'desc')->paginate(10);

    return view('backend.documents.invoice_details', [
        'title' => 'Document',
        'data' => $simcards
    ]);
    
    }

}
