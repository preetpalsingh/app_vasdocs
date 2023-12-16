<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Simcard;
use App\Models\ClientDocuments;
use App\Models\PdfGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
//use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use File;
use Log;
use App\Models\ExcelImportExport;

class DocumentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

     public $sp_user_id;
     public $sp_user_name;
 
     public function __construct(ClientDocuments $client_documents,User $user, Request $request, Auth $auth, ExcelImportExport $excel_import_export)
     { 
        $this->middleware('auth');
        $this->client_documents = $client_documents;

        $this->excel_import_export = $excel_import_export;

        $this->middleware(function ($request, $next) {

        $this->sp_user_id = Auth::user()->id;
        $this->sp_user_name = Auth::user()->first_name.' '.Auth::user()->last_name;
        $this->menu_name = 'Client Documents';
            
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
    public function index($invoice_id, $ocr_hit_status=null)
    {

        // set file type to detect image or pdf

        $file_type = 'image';
        
        $invoice_detail = ClientDocuments::where('id', $invoice_id)->orderBy('id', 'desc')->get();

        $account_codes = DB::table('account_code')->get();

        $fileName = $invoice_detail[0]->file;
        
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        
        if ($extension == 'pdf') {

            $file_type = 'pdf';
        }

        if ( Auth::user()->role_id == 1 || Auth::user()->role_id == 4 ) {

            if ( $ocr_hit_status == 'ocr-true' ) {
                $this->getDetailFromNanonets($invoice_detail[0]->id);

                return redirect()->route('admin.documentsView', ['invoice_id' => $invoice_id]);exit;
            }
            
        }

        if( $invoice_detail[0]->status_ocr_hit == 0 && $invoice_detail[0]->id == 907 && $invoice_detail[0]->status == 'Processing'  ){
            
            //$this->getDetailFromMindee($invoice_detail[0]->id);
            $this->getDetailFromNanonets($invoice_detail[0]->id);
        }

        //$invoice_detail = ClientDocuments::where('id', $invoice_id)->orderBy('id', 'desc')->get();

        $doc_user_name = '';

        if( !empty( $invoice_detail[0]->user_id ) ){

            $doc_user = User::where('id', $invoice_detail[0]->user_id)->get();

            $doc_user_name = $doc_user[0]->company_name;
        }// set previous or next documnet id

        // Get the previous ID

        if( !empty( session('ses_client_id') ) ){

            $previousId = ClientDocuments::where('id', '<', $invoice_id)
            ->where('user_id', session('ses_client_id'))
            ->where('status', $invoice_detail[0]->status)
            //->where('status', '!=', 'Archive')
            ->orderBy('id', 'desc')
            ->pluck('id')
            ->first();

            // Assign a default value if nextId is null
            $doc_previous_id = $previousId ?? 0;

            // Get the next ID
            $nextId = ClientDocuments::where('id', '>', $invoice_id)
            ->where('user_id', session('ses_client_id'))
            ->where('status', $invoice_detail[0]->status)
            ->orderBy('id', 'asc')
            ->pluck('id')
            ->first();
            
            // Assign a default value if nextId is null
            $doc_next_id = $nextId ?? 0;

        } else {

            $previousId = ClientDocuments::where('id', '<', $invoice_id)
            ->where('status', $invoice_detail[0]->status)
            ->orderBy('id', 'desc')
            ->pluck('id')
            ->first();

            // Assign a default value if nextId is null
            $doc_previous_id = $previousId ?? 0;

            // Get the next ID
            $nextId = ClientDocuments::where('id', '>', $invoice_id)
            ->where('status', $invoice_detail[0]->status)
            ->orderBy('id', 'asc')
            ->pluck('id')
            ->first();
            
            // Assign a default value if nextId is null
            $doc_next_id = $nextId ?? 0;
        }

        //echo '<pre>';print_r($invoice_detail);die();

        return view('backend.documents.index', [
            'title' => 'Document',
            'data' => $invoice_detail[0],
            'invoice_id' => $invoice_id,
            'file_type' => $file_type,
            'doc_user_name' => $doc_user_name,
            'account_codes' => $account_codes,
            'doc_previous_id' => $doc_previous_id,
            'doc_next_id' => $doc_next_id,
        ]);
     
    }

    /**
     * Get response from Mindee OCR api .
    *
    * @return \Illuminate\Http\Response
    */
    public function getDetailFromMindee($invoice_id)
    {
        
        $invoice_detail = ClientDocuments::where('id', $invoice_id)->orderBy('id', 'desc')->get();

        $filePath = public_path('documents/'.$invoice_detail[0]->file);
        
        //echo '<pre>';print_r($invoice_detail);

        /* $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.mindee.net/v1/products/mindee/invoices/v2/predict',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('document'=> new CURLFILE($filePath)),
        CURLOPT_HTTPHEADER => array(
            'Authorization: 55e5d68322bbfc5d2f736a6b086c9c95'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl); */

        $response = Http::withHeaders([
            'Authorization' => '55e5d68322bbfc5d2f736a6b086c9c95',
        ])->attach('document', file_get_contents($filePath), 'file.pdf')
          ->post('https://api.mindee.net/v1/products/mindee/invoices/v2/predict');
        
        $result = $response->json(); 

        //echo '<pre>';print_r($result['document']['inference']['pages'][0]['prediction']['date']['value']);die('dd');
        //$data = json_decode($result, true);

        $invoiceData = $result['document']['inference']['pages'][0]['prediction'];

        $invoice_date = $invoiceData['date']['value'];
        $due_date = $invoiceData['due_date']['value'];
        $invoice_number = $invoiceData['invoice_number']['value'];
        $supplier = $invoiceData['supplier']['value'];
        $net_amount = $invoiceData['total_excl']['value'];
        $total_amount = $invoiceData['total_incl']['value'];

        $this->row                          =   ClientDocuments::find($invoice_id);

        $this->row->supplier                =   $supplier;
        $this->row->invoice_number          =   $invoice_number;
        $this->row->invoice_date            =   $invoice_date;
        $this->row->due_date                =   $due_date;
        $this->row->net_amount              =   $net_amount;
        $this->row->total_amount            =   $total_amount;
        $this->row->status_ocr_hit          =   1;

        $this->row->save();

        /* // Now you can use these variables as needed
        echo "Date: $date<br>";
        echo "Due Date: $dueDate<br>";
        echo "Invoice Number: $invoiceNumber<br>";
        echo "Supplier: $supplier<br>";
        echo "Total Excl: $totalExcl<br>";
        echo "Total Incl: $totalIncl<br>";
        //echo $response;
        die(); */
     
    }

    

    /**
     * Get response from Nanonets OCR api .
    *
    * @return \Illuminate\Http\Response
    */
    public function getDetailFromNanonets($invoice_id)
    {
        
        $invoice_detail = ClientDocuments::where('id', $invoice_id)->orderBy('id', 'desc')->get();

        $filePath = public_path('documents/'.$invoice_detail[0]->file);
        
        //echo '<pre>';print_r($invoice_detail);


        /* $response = Http::withHeaders([
            'Authorization' => '55e5d68322bbfc5d2f736a6b086c9c95',
        ])->attach('document', file_get_contents($filePath), 'file.pdf')
          ->post('https://api.mindee.net/v1/products/mindee/invoices/v2/predict');  */

        $file_name = 'CH-' . str_pad($invoice_id, 6, '0', STR_PAD_LEFT).'.pdf';

        $response = Http::withHeaders([
            'accept' => 'multipart/form-data',
            'Authorization' => 'Basic ZjU1NWRjNTUtOGUwZS0xMWVlLWI3NTUtZGU3ZmYyNDNkNjBjOg==',
        ])
        ->attach('file', file_get_contents($filePath), $file_name)
        ->post('https://app.nanonets.com/api/v2/OCR/Model/73a54b61-b96b-4efc-8a33-7b4ce3531ff4/LabelFile/');

        /* $response = Http::attach(
            'file',
            file_get_contents($filePath),
            'train9.pdf'
        )->withHeaders([
            'accept' => 'multipart/form-data',
            'Authorization' => 'Basic ZjU1NWRjNTUtOGUwZS0xMWVlLWI3NTUtZGU3ZmYyNDNkNjBjOg==',
        ])->post('https://app.nanonets.com/api/v2/OCR/Model/73a54b61-b96b-4efc-8a33-7b4ce3531ff4/LabelFile/'); */

        
        $result = $response->json(); 


        //echo '<pre>';print_r($result);die();

        if (isset($result['result'][0]['prediction']) && is_array($result['result'][0]['prediction'])) {

            $prediction = $result['result'][0]['prediction'];

            $invoiceData = array();

            $invoiceData['invoice_date']['value'] = '';
            $invoiceData['due_date']['value'] = '';
            $invoiceData['invoice_number']['value'] = '';
            $invoiceData['supplier']['value'] = '';
            $invoiceData['total_amount']['value'] = '0.00';
            $invoiceData['net_amount']['value'] = '0.00';
            $invoiceData['tax_amount']['value'] = '0.00';
            $invoiceData['tax_percent']['value'] = '0';

            foreach( $prediction as $pr){

                if( !empty( $pr['ocr_text'] ) ){

                    if( $pr['label'] == 'invoice_date' ){

                        $invoiceData['invoice_date']['value'] = date("d/m/Y", strtotime($pr['ocr_text'])); 
    
                    }
    
                    if( $pr['label'] == 'payment_due_date' ){
    
                        $invoiceData['due_date']['value'] = date("d/m/Y", strtotime($pr['ocr_text'])); 
    
                    }
    
                    if( $pr['label'] == 'invoice_number' ){
    
                        $invoiceData['invoice_number']['value'] = $pr['ocr_text']; 
    
                    }
    
                    if( $pr['label'] == 'seller_name' ){
    
                        $invoiceData['supplier']['value'] = $pr['ocr_text']; 
    
                    }
    
                    if( $pr['label'] == 'invoice_amount' ){
    
                        $invoiceData['total_amount']['value'] = $this->SPConvertToFloat( $pr['ocr_text'] ); 
    
                    }
    
                    if( $pr['label'] == 'subtotal' ){
    
                        $invoiceData['net_amount']['value'] = $this->SPConvertToFloat( $pr['ocr_text'] ); 
    
                    }
    
                    if( $pr['label'] == 'total_tax' ){
    
                        $invoiceData['tax_amount']['value'] = $this->SPConvertToFloat( $pr['ocr_text'] ); 
    
                    }
    
                    if( $pr['label'] == 'total_tax_%' ){
    
                        $invoiceData['tax_percent']['value'] = (int) filter_var($pr['ocr_text'], FILTER_SANITIZE_NUMBER_INT); 
    
                    }
                }

                

                //echo '<pre>';print_r($pr);
                
            }

            //echo '<pre>';print_r($invoiceData);
            //$data = json_decode($result, true);

            //die('dd');

            $invoice_date = $invoiceData['invoice_date']['value'];
            $due_date = $invoiceData['due_date']['value'];
            $invoice_number = $invoiceData['invoice_number']['value'];
            $supplier = $invoiceData['supplier']['value'];
            $net_amount = $invoiceData['net_amount']['value'];
            $total_amount = $invoiceData['total_amount']['value'];
            $tax_amount = $invoiceData['tax_amount']['value'];
            $tax_percent = $invoiceData['tax_percent']['value'];

            $status_duplicate = 0;

            $row_check = ClientDocuments::where('invoice_number', $invoice_number )->count();

            if( $row_check > 0){

                $status_duplicate = 1;
            }

            $this->row                          =   ClientDocuments::find($invoice_id);

            $this->row->supplier                =   $supplier;
            $this->row->invoice_number          =   $invoice_number;
            $this->row->invoice_date            =   $invoice_date;
            $this->row->due_date                =   $due_date;
            $this->row->net_amount              =   $net_amount;
            $this->row->total_amount            =   $total_amount;
            $this->row->tax_amount              =   $tax_amount;
            $this->row->tax_percent             =   $tax_percent;
            $this->row->status_duplicate        =   $status_duplicate;
            $this->row->status_ocr_hit          =   1;

            $this->row->save();

        }

     
    }

    public function SPConvertToFloat($stringValue) {
        // Remove commas from the string
        $cleanedValue = str_replace(',', '', $stringValue);
    
        // Cast the cleaned string to a float with two decimal places
        $floatValue = number_format((float)$cleanedValue, 2, '.', '');
    
        return $floatValue;
    }

    public function download($invoice_id)
    {

        $invoice_detail = ClientDocuments::where('id', $invoice_id)->orderBy('id', 'desc')->get();

        // Path to your PDF file
        $pdfPath = public_path('documents').'/'.$invoice_detail[0]->file;

        // Check if the file exists
        if (file_exists($pdfPath)) {
            // Define the response headers for downloading the file
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$invoice_detail[0]->file.'"',
            ];

            // Generate a response that forces the file download
            return response()->file($pdfPath, $headers);
        } else {
            // Handle the case where the file does not exist
            return abort(404);
        }
    }

    public function invoice_details($status=null, $user_id=null)
    {
        $documents = DB::table('client_documents')
        ->join('users', 'users.id', '=', 'client_documents.user_id')
        /* ->where(function($query) use ($status , $date_range , $union_name)
        {
            
            if( $status != '' ){

                $query->where('client_documents.status', $status);
            }

            if(!empty($date_range)){

                $date_range = explode(' - ',$date_range);

                if( count( array_filter($date_range) ) > 0 ){

                    $start_date = date_format(date_create($date_range[0]),'Y-m-d');
                    $last_date = date_format(date_create($date_range[1]),'Y-m-d');
                }

                $query->whereBetween('orders.created_at', [$start_date, $last_date]);
            }

            if(!empty($union_name)){

                $query->where('orders.union_id', $union_name);
            }
        }) */
        ->select('client_documents.*', 'users.first_name', 'users.company_name')
        ->orderBy('client_documents.id', 'DESC')
        ->paginate(10);
        
        //echo '<pre>';print_r($documents);die();
        
        $clients = User::where('role_id', 3)->orderBy('id', 'desc')->get();

        $doc_user_name = '';

        if( !empty( $user_id ) ){

            $doc_user = User::where('id', $user_id)->get();

            $doc_user_name = $doc_user[0]->company_name;
        }

        return view('backend.documents.invoice_details', [
            'title' => 'Document',
            'clients' => $clients,
            'doc_status' => $status,
            'doc_user_id' => $user_id,
            'doc_user_name' => $doc_user_name
        ]);
    
    }

    public function search(Request $request)
    {


// Call the 'config:clear' command using Artisan
//Artisan::call('route:clear');
        $user = Auth::user(); // Get the authenticated user

        $query = $request->get('query');
        if( !empty( $query ) ){

            //$data = User::whereRaw('(first_name like ? or email like ? or mobile_number like ? or company_name like ?)', ['%' . $query . '%', '%' . $query . '%', '%' . $query . '%', '%' . $query . '%'])->where('role_id', 3)->paginate(10);

            $documentsQuery = DB::table('client_documents')
            ->join('users', 'users.id', '=', 'client_documents.user_id')
            ->whereRaw('(users.first_name like ? or client_documents.status like ? or client_documents.supplier like ?  )', ['%' . $query . '%', '%' . $query . '%', '%' . $query . '%'])
            ->select('client_documents.*', 'users.first_name')
            ->orderBy('client_documents.id', 'DESC');

            
        } else {

            $documentsQuery  = DB::table('client_documents')
            ->join('users', 'users.id', '=', 'client_documents.user_id')
            ->select('client_documents.*', 'users.first_name', 'users.company_name')
            ->orderBy('client_documents.id', 'DESC');

        }

        if ( Auth::user()->role_id == 3 ) {
            // Add a condition to restrict data for non-admin users
            $documentsQuery->where('client_documents.user_id', $user->id);
            $documents = $documentsQuery->paginate(10);
            
        } else {

            $documents = $documentsQuery->paginate(100);
        }

        
        

        //if ($request->ajax()) {
            return response()->json([
                'view' => view('backend.documents.list',  [
                    'title' => 'Document',
                    'data' => $documents
                ])->render(),
                'pagination' => $documents->links()->toHtml(),
            ]);
        /* }
        //die('yes');
        return view('backend.documents.list',  [
            'title' => 'Document',
            'data' => $data
        ]);  */
    }

    public function search_by_status(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user

        $query = $request->get('query');
        $doc_status = $request->get('doc_status'); 
        $doc_user_id = $request->get('doc_user_id');
        $sp_sort_filed_name = $request->get('sp_sort_filed_name');
        $sp_sort_filed_direction = $request->get('sp_sort_filed_direction');

        if( !empty( $query ) ){

            //$data = User::whereRaw('(first_name like ? or email like ? or mobile_number like ? or company_name like ?)', ['%' . $query . '%', '%' . $query . '%', '%' . $query . '%', '%' . $query . '%'])->where('role_id', 3)->paginate(10);

            $documentsQuery = DB::table('client_documents')
            ->join('users', 'users.id', '=', 'client_documents.user_id')
            ->leftJoin('users as session_users', 'session_users.id', '=', 'client_documents.session_id')
            //->where('client_documents.status', $doc_status)
            ->whereRaw('(client_documents.supplier like ?  )', [ '%' . $query . '%'])
            ->select('client_documents.*', 'users.first_name', 'users.company_name', 
            'session_users.first_name as session_first_name');
            //->orderBy('client_documents.id', 'DESC');
            
            if ( $doc_status != 'all' ) {
                
                $documentsQuery->where('client_documents.status', $doc_status);

            } else {

                $documentsQuery->where('client_documents.status' , '!=' , 'Archive');

            }
            
            if ( !empty( $doc_user_id ) ) {

                $documentsQuery->where('client_documents.user_id', $doc_user_id);
            }

            if ( Auth::user()->role_id == 3 ) {
                // Add a condition to restrict data for non-admin users
                $documentsQuery->where('client_documents.user_id', $user->id);
            }

        } else {

            $documentsQuery = DB::table('client_documents')
            ->join('users', 'users.id', '=', 'client_documents.user_id')
            ->leftJoin('users as session_users', 'session_users.id', '=', 'client_documents.session_id')
            //->where('client_documents.status', $doc_status)
            ->select('client_documents.*', 'users.first_name', 'users.company_name', 
            'session_users.first_name as session_first_name');
            //->orderBy('client_documents.id', 'DESC');
            
            if ( $doc_status != 'all' ) {
                // Add a condition to restrict data for non-admin users
                $documentsQuery->where('client_documents.status', $doc_status);
            } else {

                $documentsQuery->where('client_documents.status' , '!=' , 'Archive');

            }
            
            if ( !empty( $doc_user_id ) ) {

                $documentsQuery->where('client_documents.user_id', $doc_user_id);
            }

            if ( Auth::user()->role_id == 3 ) {
                // Add a condition to restrict data for non-admin users
                $documentsQuery->where('client_documents.user_id', $user->id);
            }

        }

        if( $sp_sort_filed_name == 'company_name' ){

            $documentsQuery->orderBy('users.company_name', $sp_sort_filed_direction);

        } else {

            $documentsQuery->orderBy('client_documents.'.$sp_sort_filed_name, $sp_sort_filed_direction);

        }

        if ( Auth::user()->role_id == 3 ) {

            $documents = $documentsQuery->paginate(10);
            
        } else {

            $documents = $documentsQuery->paginate(100);
        }

        if ( !empty( $doc_user_id ) ) {

            // Set a session variable
            session(['ses_client_id' => $doc_user_id]);

        } else {

            session()->forget('ses_client_id');
        }
        

        //if ($request->ajax()) {
            return response()->json([
                'view' => view('backend.documents.list',  [
                    'title' => 'Document',
                    'data' => $documents,
                    'sp_sort_filed_name' => $sp_sort_filed_name,
                    'sp_sort_filed_direction' => $sp_sort_filed_direction
                ])->render(),
                'pagination' => $documents->links()->toHtml(),
            ]);
        /* }
        //die('yes');
        return view('backend.documents.list',  [
            'title' => 'Document',
            'data' => $data
        ]);  */
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function invoice_add(Request $request)
    {

        if ( Auth::user()->role_id == 1 || Auth::user()->role_id == 4 ) {

            // Validation
            $validate = Validator::make([
                'user_id'              =>      $request->get('user_id'),
                //'file'             =>      $request->get('file'),
            ], [
                'user_id'              =>      'required',
                //'file'             =>      'required',
            ]);

            // If Validations Fails
            if($validate->fails()){

                return response()->json([
                    'status' => false,
                    'message' => $validate->errors()->first()
                ], 200);

            }

        }
		
        try {
            
            $session_id = Auth::user()->id;

            if ( Auth::user()->role_id == 1 || Auth::user()->role_id == 4 ) {

                $user_id = $request->get('user_id');

            } else {

                $user_id = Auth::user()->id;
            }

            $allowedFileTypes = ['jpg', 'jpeg', 'png', 'pdf'];

            //print_r($request->file('file'));die('fff');

            if ($request->hasFile('file')) {

                $file = $request->file('file');
                $file_path = $file->getPathName();
                $originalFileName = $file->getClientOriginalName();
                $originalFileNameWithoutExt = pathinfo($originalFileName,PATHINFO_FILENAME);
                $fileExtension = strtolower($file->getClientOriginalExtension());
        
                if (in_array($fileExtension, $allowedFileTypes)) {
    
                    $fileName = time() . '_' . str_replace(' ', '_', $originalFileName);
                    //$file->storeAs('documents', $fileName, 'public');
                    //$path = $request->file('file')->store('public/files');

                    if( $fileExtension == 'pdf' ){

                        if ($file->move(public_path('documents'), $fileName)) {
     
                            $this->client_documents->user_id            =   $user_id;
                            $this->client_documents->session_id         =   $session_id;
                            $this->client_documents->file               =   $fileName;
                            $this->client_documents->created_at         =   date('Y-m-d H:i:s');
        
                            $this->client_documents->save();
        
                            return response()->json([
                                'status' => true,
                                'message' => 'File uploaded successfully'
                            ], 200);
    
                        } else {
    
                            return response()->json([
                                'status' => false,
                                'message' => 'File upload failed'
                            ], 200);
    
                        }


                    } else {

                        $fileName = time() . '_' . str_replace(' ', '_', $originalFileNameWithoutExt).'.pdf';

                        $pdf = new PdfGenerator();
                        $pdf->SaveSinglePdf( $file_path , $fileName );

                        $this->client_documents->user_id            =   $user_id;
                        $this->client_documents->session_id         =   $session_id;
                        $this->client_documents->file               =   $fileName;
                        $this->client_documents->created_at         =   date('Y-m-d H:i:s');

                        $this->client_documents->save();

                        return response()->json([
                            'status' => true,
                            'message' => 'File uploaded successfully'
                        ], 200);
                    }
     
    
                } else {
    
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid file type. Allowed types: jpg, jpeg, png, pdf',
                    ], 200);
                    
                }

            } else{

                return response()->json([
                    'status' => false,
                    'message' => 'Please select file to upload'
                ], 200);

            }


        } catch(\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function invoice_update(Request $request)
    {

       //print_r($request->get('edit_id'));die('A');
        // Validation
        $validate = Validator::make([
            'edit_id'              =>      $request->get('edit_id'),
            //'file'             =>      $request->get('file'),
        ], [
            'edit_id'              =>      'required',
            //'file'             =>      'required',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }
		
        try {

            $status_duplicate = 0;

            $row_check = ClientDocuments::where('invoice_number', $request->get('invoice_number'))->count();

            if( $row_check > 0){

                $status_duplicate = 1;
            }

            $edit_id                            =   $request->get('edit_id');

            $this->row                          =   ClientDocuments::find($edit_id);

			$this->row->supplier                =   $request->get('supplier');
			$this->row->invoice_number          =   $request->get('invoice_number');
			$this->row->invoice_date            =   $request->get('invoice_date');
			$this->row->due_date                =   $request->get('due_date');
			$this->row->net_amount              =   $request->get('net_amount');
			$this->row->tax_percent             =   $request->get('tax_percent');
			$this->row->tax_amount              =   $request->get('tax_amount');
			$this->row->total_amount            =   $request->get('total_amount');
			$this->row->account_code            =   $request->get('account_code');
			$this->row->payment_method          =   $request->get('payment_method');
			$this->row->standard_vat            =   $request->get('standard_vat');
			$this->row->status                  =   $request->get('status');
			$this->row->status_duplicate        =   $status_duplicate;
            $this->row->updated_at              =   date('Y-m-d H:i:s');

            $this->row->save();

            // insert action

            /* $log_data_values = array(
				'user_id' => $this->sp_user_id,
				'name' => $this->sp_user_name,
				'record_id' => $this->simcard->id,
				'menu' => $this->menu_name,
				'action' => 'Add',
				'curent_route' => $this->curent_route,
				'request_data' => $request->all(),
                'updated_at' => date('Y-m-d H:i:s')
			);

            Log::debug("Sp Log Store", $log_data_values ); */
			
            $msg_arr = 'Record has been updated';
			
            //$this->products->save();
			
            return response()->json([
                'status' => true,
                //'msg_arr' => $msg_arr,
                'message' => $msg_arr 
            ], 200);

            

            

        } catch(\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Technical Error!'
                //'message' => $e->getMessage()
            ], 200);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function update_status(Request $request)
    { 

        try {

            $edit_id                            =   $request->get('edit_id');

            $this->row                          =   ClientDocuments::find($edit_id);

			$this->row->status                  =   'Archive';
            $this->row->updated_at              =   date('Y-m-d H:i:s');

            $this->row->save();

            // insert action

            /* $log_data_values = array(
				'user_id' => $this->sp_user_id,
				'name' => $this->sp_user_name,
				'record_id' => $this->simcard->id,
				'menu' => $this->menu_name,
				'action' => 'Add',
				'curent_route' => $this->curent_route,
				'request_data' => $request->all(),
                'updated_at' => date('Y-m-d H:i:s')
			);

            Log::debug("Sp Log Store", $log_data_values ); */
			
            return response()->json([
                'status' => true,
                //'msg_arr' => $msg_arr,
                'message' => 'Archived Invoice successfully.' 
            ], 200);


        } catch(\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Technical Error!'
                //'message' => $e->getMessage()
            ], 200);

        }
    }

    // export Documnets details in excel format

    public function export(Request $request) 
    { 
        //$user_fileds = array('first_name', 'last_name', 'email', 'mobile_number');
        
        /* $start_date = '2023-09-01';
        $end_date = '2023-09-16'; */

        $status      =    $request->post('status');
        $action_status      =    $request->post('action_status');
        $client_id      =    $request->post('client_id');
        $multi_doc_ids_for_csv      =    $request->post('multi_doc_ids_for_csv');

        if( !empty( $action_status ) ){

            $doc_ids     =    $multi_doc_ids_for_csv;

            $this->excel_import_export->excel_export_documnets_by_ids( $doc_ids, $client_id, $status, $action_status );

        } else {

            $start_date     =    $request->post('start_date');
            $end_date       =    $request->post('end_date');
            $status         =    $request->post('status');

            $start_date     =   date("Y-m-d", strtotime($start_date));
            $end_date       =   date("Y-m-d", strtotime($end_date)); 

            if( !empty( $status ) ){
                
                DB::table('client_documents')->where('id' , '!=' , 0)->where('user_id' , $client_id)->where('net_amount' , '!=' , NULL)
                ->whereRaw("  DATE(created_at) >= '".$start_date."' AND DATE(created_at) <= '".$end_date."'")->update(['status' => $status]);

            }

            $result = $this->excel_import_export->excel_export_documnets( $start_date, $end_date, $client_id);


        }

        
    }

    // Multiple select update status

    public function multi_select_update_status(Request $request) 
    {

        $validate = Validator::make([
            'edit_ids'              =>      $request->get('edit_ids'),
            'status'             =>      $request->get('status'),
        ], [
            'edit_ids'              =>      'required',
            'status'             =>      'required',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }
        
        try {

            $edit_ids                            =   $request->get('edit_ids');
            $status                            =   $request->get('status');

            $ids = explode(',', $edit_ids);

            ClientDocuments::whereIn('id', $ids)
                ->update(['status' => $status]);

            // insert action

            /* $log_data_values = array(
				'user_id' => $this->sp_user_id,
				'name' => $this->sp_user_name,
				'record_id' => $this->simcard->id,
				'menu' => $this->menu_name,
				'action' => 'Add',
				'curent_route' => $this->curent_route,
				'request_data' => $request->all(),
                'updated_at' => date('Y-m-d H:i:s')
			);

            Log::debug("Sp Log Store", $log_data_values ); */
			
            return response()->json([
                'status' => true,
                //'msg_arr' => $msg_arr,
                'message' => 'Records status updated successfully.' 
            ], 200);


        } catch(\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Technical Error!'
                //'message' => $e->getMessage()
            ], 200);

        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    { 

        $delete_id = $request->get('delete_id');// check document related to client

        if ( Auth::user()->role_id == 3 ) {
            
            $user_id = Auth::user()->id;

            $count_row = ClientDocuments::where('user_id', $user_id)->where('id', $delete_id)->count();

            if( $count_row < 1){

                return response()->json(['status' => false], 200);
            }

        }


        $delete_data = DB::table('client_documents')->where('id', $delete_id)->get();

        $previous_img_val = $delete_data[0]->file;

        if ( ! empty( $previous_img_val ) ) {
        
            $file_del = public_path('documents').'/'.$previous_img_val;
        
            if (is_file($file_del) && file_exists($file_del)) {
            
                $st = File::delete($file_del);
                
            }
        }

        ClientDocuments::find($delete_id)->delete();
       
        //$this->row->find($delete_id)->delete();

        // insert action

        /* $log_data_values = array(
            'user_id' => $this->sp_user_id,
            'name' => $this->sp_user_name,
            'record_id' => $delete_id,
            'menu' => 'Product',
            'action' => 'Delete',
            'curent_route' => $this->curent_route,
            'request_data' => $request->all(),
            'updated_at' => date('Y-m-d H:i:s')
        );

        Log::debug("Sp Log Store", $log_data_values ); */
        
        return response()->json(['status' => true], 200);
    }

}
