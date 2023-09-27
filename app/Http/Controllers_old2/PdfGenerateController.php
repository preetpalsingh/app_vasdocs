<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
use PDF;
use Spatie\Browsershot\Browsershot;

class PdfGenerateController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function pdfview(Request $request)
    {
        $users = DB::table("users")->get();
        //view()->share('users',$users);

        if($request->has('download')) {
            $browsershot = new Browsershot();
            //Browsershot::html('<h1>Hello world!!</h1>')->setNodeBinary('PATH %~dp0;%PATH%;')->save('example.pdf');die();
            Browsershot::url('https://example.com')->setNodeBinary('PATH %~dp0;%PATH%;')->bodyHtml();die();
        	// pass view file
            $pdf = PDF::loadView('pdfview');
            // download pdf
            return $pdf->download('userlist.pdf');
        }
        //return view('pdfview');

        return view('pdfview', [
            'title' => 'pdfview',
            'users' => $users
        ]);
    }
}
