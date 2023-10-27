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
    public function index($invoice_id)
    {

        // Get the full path to the CSV file in the public directory
        /* $csvFilePath = public_path('ChartOfAccounts.csv');

        // Check if the CSV file exists and can be opened
        if (($handle = fopen($csvFilePath, 'r')) !== false) {
            // Initialize an array to store CSV data
            $csvData = [];

            $i = 0;

            // Read and process each row of the CSV file
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {

                //print_r($data);die();

                if( $i > 0){

                    

                    //$name = $data[0].'-'.$data[1].'-'.$data[2];

                    $record = [
                        'code' => $data[0], 
                        'report_code' => $data[1], 
                        'name' => $data[2], 
                        'created_at' => date('Y-m-d H:i:s'),
                        // Add more columns as needed
                    ];
                    
                    // Insert data into the table using a DB query
                    DB::table('account_code')->insert($record);

                // Process each row of the CSV file here
                // For example, you can add the row data to the $csvData array
                $csvData[] = $data;
                }

                

                $i++;
            }

            // Close the CSV file
            fclose($handle);

            // Pass the CSV data to a view or perform further processing
            return view('csv_data', ['csvData' => $csvData]);
        } else {
            // Handle the case where the CSV file cannot be opened
            return view('file_not_found');
        }
        die('yes'); */

        // set file type to detect image or pdf

        $file_type = 'image';
        
        $invoice_detail = ClientDocuments::where('id', $invoice_id)->orderBy('id', 'desc')->get();

        $account_codes = DB::table('account_code')->get();

        $fileName = $invoice_detail[0]->file;
        
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        
        if ($extension == 'pdf') {

            $file_type = 'pdf';
        }

        //echo '<pre>';print_r($invoice_detail);die();

        return view('backend.documents.index', [
            'title' => 'Document',
            'data' => $invoice_detail[0],
            'invoice_id' => $invoice_id,
            'file_type' => $file_type,
            'account_codes' => $account_codes
        ]);
     
    }

    /**
     * Get response from OCR api .
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

        echo '<pre>';print_r($result['document']);die('dd');
        $data = json_decode($result, true);

        $invoiceData = $data['document']['inference']['pages'][0]['prediction'];

        $date = $invoiceData['date']['value'];
        $dueDate = $invoiceData['due_date']['value'];
        $invoiceNumber = $invoiceData['invoice_number']['value'];
        $supplier = $invoiceData['supplier']['value'];
        $totalExcl = $invoiceData['total_excl']['value'];
        $totalIncl = $invoiceData['total_incl']['value'];

        // Now you can use these variables as needed
        echo "Date: $date<br>";
        echo "Due Date: $dueDate<br>";
        echo "Invoice Number: $invoiceNumber<br>";
        echo "Supplier: $supplier<br>";
        echo "Total Excl: $totalExcl<br>";
        echo "Total Incl: $totalIncl<br>";
        //echo $response;
        die();
     
    }

    public function client($invoice_id)
    {
        
    /*     $simcards = ClientDocuments::orderBy('id', 'desc')->paginate(10);

        return view('backend.documents.client', [
            'title' => 'Document',
            'data' => $simcards
        ]); */

        $invoice_detail = ClientDocuments::where('id', $invoice_id)->orderBy('id', 'desc')->get();

        //echo '<pre>';print_r($invoice_detail);die();

        return view('backend.documents.index_test', [
            'title' => 'Document',
            'data' => $invoice_detail[0],
            'invoice_id' => $invoice_id
        ]);
    
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

    public function client_view($invoice_id)
    {
      
        $invoice_detail = ClientDocuments::where('id', $invoice_id)->orderBy('id', 'desc')->get();

        echo '<pre>';print_r($invoice_detail);die();
    
        return view('backend.documents.client_view', [  
            'title' => 'Document',
            'data' => $invoice_detail
        ]);
    
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

            if ( Auth::user()->role_id == 3 ) {
                // Add a condition to restrict data for non-admin users
                $documentsQuery->where('client_documents.user_id', $user->id);
            }

            $documents = $documentsQuery->paginate(10);

        } else {

            $documentsQuery  = DB::table('client_documents')
            ->join('users', 'users.id', '=', 'client_documents.user_id')
            ->select('client_documents.*', 'users.first_name', 'users.company_name')
            ->orderBy('client_documents.id', 'DESC');

            if ( Auth::user()->role_id == 3 ) {
                // Add a condition to restrict data for non-admin users
                $documentsQuery->where('client_documents.user_id', $user->id);
            }

            $documents = $documentsQuery->paginate(10);
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

        if( !empty( $query ) ){

            //$data = User::whereRaw('(first_name like ? or email like ? or mobile_number like ? or company_name like ?)', ['%' . $query . '%', '%' . $query . '%', '%' . $query . '%', '%' . $query . '%'])->where('role_id', 3)->paginate(10);

            $documentsQuery = DB::table('client_documents')
            ->join('users', 'users.id', '=', 'client_documents.user_id')
            //->where('client_documents.status', $doc_status)
            ->whereRaw('(client_documents.supplier like ?  )', [ '%' . $query . '%'])
            ->select('client_documents.*', 'users.first_name', 'users.company_name')
            ->orderBy('client_documents.id', 'DESC');
            
            if ( $doc_status != 'all' ) {
                
                $documentsQuery->where('client_documents.status', $doc_status);
            }
            
            if ( !empty( $doc_user_id ) ) {

                $documentsQuery->where('client_documents.user_id', $doc_user_id);
            }

            if ( Auth::user()->role_id == 3 ) {
                // Add a condition to restrict data for non-admin users
                $documentsQuery->where('client_documents.user_id', $user->id);
            }

            $documents = $documentsQuery->paginate(10);

        } else {

            $documentsQuery = DB::table('client_documents')
            ->join('users', 'users.id', '=', 'client_documents.user_id')
            //->where('client_documents.status', $doc_status)
            ->select('client_documents.*', 'users.first_name', 'users.company_name')
            ->orderBy('client_documents.id', 'DESC');
            
            if ( $doc_status != 'all' ) {
                // Add a condition to restrict data for non-admin users
                $documentsQuery->where('client_documents.status', $doc_status);
            }
            
            if ( !empty( $doc_user_id ) ) {

                $documentsQuery->where('client_documents.user_id', $doc_user_id);
            }

            if ( Auth::user()->role_id == 3 ) {
                // Add a condition to restrict data for non-admin users
                $documentsQuery->where('client_documents.user_id', $user->id);
            }

            $documents = $documentsQuery->paginate(10);
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

    // export Documnets details in excel format

    public function export(Request $request) 
    { 
        //$user_fileds = array('first_name', 'last_name', 'email', 'mobile_number');
        
        /* $start_date = '2023-09-01';
        $end_date = '2023-09-16'; */

        $start_date     =    $request->post('start_date');
        $end_date       =    $request->post('end_date');

        $start_date     =   date("Y-m-d", strtotime($start_date));
        $end_date       =   date("Y-m-d", strtotime($end_date));

        $result = $this->excel_import_export->excel_export_documnets( $start_date, $end_date); 
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
