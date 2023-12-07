<?php

/*copyright@2018 Radical Enlighten Co.,ltd.*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use App\Models\UnionMembership;
use App\Models\User;

use Mail;

class ExcelImportExport extends Model
{

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct( UnionMembership $union_membership )
    {
		$this->union_membership = $union_membership;
    }

    /**
     * Get the user list from users table.
     */
	 
    public function excel_export_users( $user_fileds )
    { 
		
		require_once 'excel_import_export/php-excel.class.php';
		
		$user_fileds_ex = implode( ',' , $user_fileds);
		
		$users_list = DB::select('SELECT '.$user_fileds_ex.' FROM users  ');
		
		$file_name = 'ExportList_'.date("YmdHis");
		
		$rand_pawd = rand();
		
		$filed_name_arr[] = '#';
		
		foreach($user_fileds as $filed){
			
			$filed_name_arr[] = ucfirst($filed);
		}

		$data_export = array(1 => $filed_name_arr);
		$i=1;
		foreach($users_list as $row){
			
			$filed_val_arr = array();
			$filed_val_arr[] = $i;
			
			foreach($user_fileds as $filed){
			
				$filed_val_arr[] = $row->$filed;
			}
			array_push($data_export, $filed_val_arr);
			
			$i++;
			
		}
		
		$created_file_name = $file_name.'.xls';
		
		$xls = new \Excel_XML('UTF-8', false, 'User List');
		$xls->addArray($data_export);
		$xls->generateXML($file_name);
     
		exit(); 
    }

	public function excel_import_users()
    { 
		if (($handle = fopen($_FILES['file']['tmp_name'], "r")) !== FALSE) {

			$i = 1;
		
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

				if($i != 1){

					$imp_data = array_filter($data);

					$union_name 		= 		$imp_data['0'];
					$union_member_ID 	= 		$imp_data['0'];
					$name 				= 		$imp_data['0'];
					$surname 			= 		$imp_data['0'];
					$address 			= 		$imp_data['0'];
					$phone_number 		= 		$imp_data['0'];

					$row_check = UnionMembership::where('union_member_ID', $union_member_ID)->count();
							
					if($row_check < 1){

						$this->union_membership->union_name = $union_name;
						$this->union_membership->union_member_ID = $union_member_ID;
						$this->union_membership->name = $name;
						$this->union_membership->surname = $surname;
						$this->union_membership->address = $address;
						$this->union_membership->phone_number = $phone_number;
						$this->union_membership->status = 1;
						$this->union_membership->created_at = date('Y-m-d H:i:s');

						$this->union_membership->save();

					}

				}

				$i++;

			}

		}
     
		//exit(); 
    }

    /**
     * Get the user list from users table.
     */
	 
    public function excel_export_documnets_old( $start_date, $end_date )
    { 
		
		require_once 'excel_import_export/php-excel.class.php';

		$user = Auth::user();

		$fileds = array('supplier','invoice_number','invoice_date','due_date','net_amount','tax_amount','total_amount','status', 'code', 'report_code', 'name');
		
		$fileds_im = implode( ',' , $fileds);

		if ( Auth::user()->role_id == 3 ) {
		
			$row_list = DB::select("SELECT $fileds_im  FROM client_documents t1 LEFT JOIN account_code t2 ON t1.account_code = t2.id where t1.user_id = ".$user->id." AND net_amount != '' AND  date(t1.created_at) >= '".$start_date."' AND date(t1.created_at) <=  '".$end_date."'");

		} else {

			$row_list = DB::select("SELECT $fileds_im  FROM client_documents t1 LEFT JOIN account_code t2 ON t1.account_code = t2.id where net_amount != '' AND  date(t1.created_at) >= '".$start_date."' AND date(t1.created_at) <=  '".$end_date."'");

		}
		
		$file_name = 'ExportList_'.date("YmdHis");
		
		$rand_pawd = rand();
		
		$filed_name_arr[] = '#';
		
		foreach($fileds as $filed){
			
			$filed_name_arr[] = ucfirst($filed);
		}

		$data_export = array(1 => $filed_name_arr);
		$i=1;
		foreach($row_list as $row){
			
			$filed_val_arr = array();
			$filed_val_arr[] = $i;
			
			foreach($fileds as $filed){
			
				$filed_val_arr[] = $row->$filed;
			}
			array_push($data_export, $filed_val_arr);
			
			$i++;
			
		}
		
		$created_file_name = $file_name.'.xls';
		
		$xls = new \Excel_XML('UTF-8', false, 'Documents List');
		$xls->addArray($data_export);
		$xls->generateXML($file_name);
     
		exit(); 
    }

    /**
     * Get the user list from Documents table.
     */
	 
    public function excel_export_documnets( $start_date, $end_date, $client_id )
    { 
		
		require_once 'excel_import_export/php-excel.class.php';

		$user = Auth::user();

		$fileds = array('supplier','invoice_number','invoice_date','due_date','net_amount','tax_amount','tax_percent','standard_vat','total_amount', 'status', 'payment_method', 'code', 'report_code', 'name');

		$csv_fileds_name = array('Supplier','Invoice No.','Invoice Date','Due Date','Net Amount','Tax Amount','Tax Rate(%)','Standard Vat','Gross Amount', 'Status', 'Payment Board', 'Code', 'Report Code', 'Name');
		
		$fileds_im = implode( ',' , $fileds);

		if ( Auth::user()->role_id == 3 ) {
		
			$row_list = DB::select("SELECT $fileds_im  FROM client_documents t1 LEFT JOIN account_code t2 ON t1.account_code = t2.id where t1.user_id = ".$user->id." AND  date(t1.created_at) >= '".$start_date."' AND date(t1.created_at) <=  '".$end_date."'");

		} else {
			
			$row_list = DB::select("SELECT $fileds_im  FROM client_documents t1 LEFT JOIN account_code t2 ON t1.account_code = t2.id where t1.user_id = ".$client_id." AND  date(t1.created_at) >= '".$start_date."' AND date(t1.created_at) <=  '".$end_date."'");

		}
		
		$file_name = 'ExportList_'.date("YmdHis");
		
		$created_file_name = $file_name.'.csv';
		
		$this->csv_export($csv_fileds_name, $row_list, $created_file_name);

		exit();
    }

    /**
     * Get the user list from Documents table.
     */
	 
    public function excel_export_documnets_by_ids( $doc_ids, $client_id )
    { 
		
		require_once 'excel_import_export/php-excel.class.php';

		$user = Auth::user();

		$fileds = array('supplier','invoice_number','invoice_date','due_date','net_amount','tax_amount','tax_percent','standard_vat','total_amount', 'status', 'payment_method', 'code', 'report_code', 'name');

		$csv_fileds_name = array('Supplier','Invoice No.','Invoice Date','Due Date','Net Amount','Tax Amount','Tax Rate(%)','Standard Vat','Gross Amount', 'Status', 'Payment Board', 'Code', 'Report Code', 'Name');
		
		$fileds_im = implode( ',' , $fileds);

		if ( Auth::user()->role_id == 3 ) {
		
			$row_list = DB::select("SELECT $fileds_im  FROM client_documents t1 LEFT JOIN account_code t2 ON t1.account_code = t2.id where t1.user_id = ".$user->id." AND t1.id IN(".$doc_ids.") ");

		} else {
			
			$row_list = DB::select("SELECT $fileds_im  FROM client_documents t1 LEFT JOIN account_code t2 ON t1.account_code = t2.id where t1.user_id = ".$client_id." AND  t1.id IN(".$doc_ids.") ");

		}
		
		$file_name = 'ExportList_'.date("YmdHis");
		
		$created_file_name = $file_name.'.csv';
		
		$this->csv_export($csv_fileds_name, $row_list, $created_file_name);

		exit();
    }

	/**
     * Get the user list from Documents table.
     */
	 
	 public function csv_export( $fileds, $row_list, $file_name )
	 { 
		 
		
		 // Set the HTTP response headers
		 header('Content-Type: text/csv');
		 header('Content-Disposition: attachment; filename="'.$file_name.'"');
 
		 // Open the output stream
		 $output = fopen('php://output', 'w');
 
		 fputcsv($output, $fileds);
		 
		 // Write the data to the CSV file
		 foreach ($row_list as $row) {
 
			$row = (array) $row;
 
			fputcsv($output, $row);
		 }
 
		 // Close the output stream
		 fclose($output);
 
		 exit();
	 }

    
}
