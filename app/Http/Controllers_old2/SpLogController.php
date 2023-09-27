<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SpLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SpLogController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */

 
     public function __construct( SpLog $sp_log, Request $request )
     { 
        $this->middleware('auth');
        $this->middleware('permission:log-list|log-delete', ['only' => ['index']]);
        $this->middleware('permission:log-delete', ['only' => ['destroy']]);
        $this->sp_log = $sp_log;
     }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $logs = SpLog::where('level_name', 'DEBUG')->orderBy('created_at', 'desc')->paginate(10);
       
        return view('log.index', [
            'title' => 'Log',
            'logs' => $logs
        ]);
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    { 

        $delete_id = $request->get('delete_id');

        $this->sp_log->find($delete_id)->delete();
        
        return response()->json(['status' => true], 200);
    }
}
