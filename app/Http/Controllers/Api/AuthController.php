<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller; 
use App\Models\User;
use App\Models\ClientDocuments;
use App\Models\PdfGenerator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

            if( $user->status == 1){

                $token = $user->createToken('authToken')->accessToken;
                $token = $user->createToken('authToken')->plainTextToken;

                //$user->api_token = Str::random(60); // Generate a random token
                $user->api_token = $token; 
                $user->save();

                return response()->json(['token' => $user->api_token,'status' => true], 200);
                
            } else {

                return response()->json(['msg' => 'Your account is inactive.','status' => false], 401);
            }
            

        } else {
            return response()->json(['msg' => 'Wrong email or password !','status' => false], 401);
        }
    }

    public function user(Request $request)
    { 
        $user = $request->user();
        return response()->json($user);
    }

    public function upload_document_old(Request $request)
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

                    $document_size = $this->formatfileConvertsize( filesize(public_path('documents').'/'.$this->client_documents->file) );

                    $files = array(
                        'title' => $this->client_documents->file,
                        'size' => $document_size,
                        'date' => date_format( date_create( $this->client_documents->created_at ), 'Y-m-d' ),
                        //'image' => 'assets/images/pdf-icon.png',
                    );

                    return response()->json(['message' => 'File uploaded successfully','files' => $files], 200);
                } else {
                    return response()->json(['message' => 'File upload failed'], 500);
                }

            } else {

                return response()->json(['message' => 'Invalid file type. Allowed types: jpg, jpeg, png'], 400);
                
            }
        }

        return response()->json(['message' => 'Please select file to upload'], 400);
    }

    public function upload_document(Request $request)
    {
        $user = $request->user();

        //dd($request->file('file'));

        $allowedFileTypes = ['jpg', 'jpeg', 'png', 'pdf'];

        if ($request->hasFile('file')) {

            $file = $request->file('file');
            $file_path = $file->getPathName();
            //echo '<pre>';print_r($file->getPathName());die();
            $originalFileName = $file->getClientOriginalName();
            $originalFileNameWithoutExt = pathinfo($originalFileName,PATHINFO_FILENAME);
            $fileExtension = strtolower($file->getClientOriginalExtension());
    
            if (in_array($fileExtension, $allowedFileTypes)) {

                if( $fileExtension == 'pdf' ){

                    $fileName = time() . '_' . str_replace(' ', '_', $originalFileName);
    
                    if ($file->move(public_path('documents'), $fileName)) {

                        $this->client_documents->user_id            =   $user->id;
                        $this->client_documents->file               =   $fileName;
                        $this->client_documents->created_at         =   date('Y-m-d H:i:s');

                        $this->client_documents->save();

                        $document_size = $this->formatfileConvertsize( filesize(public_path('documents').'/'.$this->client_documents->file) );

                        $files = array(
                            'title' => $this->client_documents->file,
                            'size' => $document_size,
                            'date' => date_format( date_create( $this->client_documents->created_at ), 'Y-m-d' ),
                            //'image' => 'assets/images/pdf-icon.png',
                        );

                        return response()->json(['message' => 'File uploaded successfully','files' => $files], 200);
                    } else {
                        return response()->json(['message' => 'File upload failed'], 500);
                    }


                } else {

                    $fileName = time() . '_' . str_replace(' ', '_', $originalFileNameWithoutExt).'.pdf';
                
                    $pdf = new PdfGenerator();
                    $pdf->SaveSinglePdf( $file_path , $fileName );

                    $this->client_documents->user_id            =   $user->id;
                    $this->client_documents->file               =   $fileName;
                    $this->client_documents->created_at         =   date('Y-m-d H:i:s');

                    $this->client_documents->save();

                    $document_size = $this->formatfileConvertsize( filesize(public_path('documents').'/'.$this->client_documents->file) );

                    $files = array(
                        'title' => $this->client_documents->file,
                        'size' => $document_size,
                        'date' => date_format( date_create( $this->client_documents->created_at ), 'Y-m-d' ),
                        //'image' => 'assets/images/pdf-icon.png',
                    );

                    return response()->json(['message' => 'File uploaded successfully','files' => $files], 200);
                }


            } else {

                return response()->json(['message' => 'Invalid file type. Allowed types: jpg, jpeg, png, pdf'], 400);
                
            }
        }

        return response()->json(['message' => 'Please select file to upload'], 400);
    }

    public function getClientDocumentList(Request $request)
    {

        $user = $request->user();

        

        /* // Sample data (replace with your actual data)
        $files = [
            [
                'title' => 'Document 1',
                'size' => '2.5 MB',
                'date' => '2023-09-19',
                'image' => 'assets/images/pdf-icon.png',
            ],
            [
                'title' => 'Image 1',
                'size' => '1.2 MB',
                'date' => '2023-09-18',
                'image' => 'assets/images/pdf-icon.png',
            ],
            [
                'title' => 'Image 2',
                'size' => '1.5 MB',
                'date' => '2023-09-18',
                'image' => 'assets/images/pdf-icon.png',
            ],
            // Add more items as needed
        ]; */

        $files = array();

        $doc_user_id = $user->id;

        $documentsQuery = DB::table('client_documents')
            ->where('client_documents.user_id', $doc_user_id)
            ->select('client_documents.*')
            ->orderBy('client_documents.id', 'DESC');

       $documents = $documentsQuery->paginate(2);

       //echo '<pre>';print_r($documents);die();

       if( count(  $documents  ) > 0 ){

            foreach( $documents as $document){

                $document_size = $this->formatfileConvertsize( filesize(public_path('documents').'/'.$document->file) );

                $files[] = array(
                    'title' => $document->file,
                    'size' => $document_size,
                    'date' => date_format( date_create( $document->created_at ), 'Y-m-d' ),
                    //'image' => 'assets/images/pdf-icon.png',
                );
            }

            
       }

        return response()->json($files);
    }

    public function getClientDocumentListTest(Request $request, $last_id = null)
    {

        $last_id = $last_id;
        $files = array();

        $documentsQuery = DB::table('client_documents')
            ->select('client_documents.*')
            ->orderBy('client_documents.id', 'DESC');

        if( !empty( $last_id ) ){

            $documentsQuery->where('client_documents.id', '<' , $last_id);
        }

       $documents = $documentsQuery->paginate(10);

       //echo '<pre>';print_r($documents);die();

       if( count(  $documents  ) > 0 ){

            foreach( $documents as $document){

                $document_size = $this->formatfileConvertsize( filesize(public_path('documents').'/'.$document->file) );

                $files[] = array(
                    'id' => $document->id,
                    'title' => $document->file,
                    'size' => $document_size,
                    'date' => date_format( date_create( $document->created_at ), 'Y-m-d' ),
                    //'image' => 'assets/images/pdf-icon.png',
                );
            }

            
       }

        return response()->json($files);
    }


    public function formatfileConvertsize($value) {
        //convert Bytes Logic
        if($value < 1024) {
            return $value . " bytes";
        }
        //convert Kilobytes Logic
        else if($value < 1024000) {
            return round(($value / 1024 ), 1) . " k";
        }
        //convert Megabytes Logic
        else {
            return round(($value / 1024000), 1) . " MB";
        }
    }

    public function generatePdf(Request $request)
    {
        $pdf = new PdfGenerator();
        $content = 'This is the content of your PDF. Customize it as needed.';
        $pdf->generatePdf($content);
    }

}