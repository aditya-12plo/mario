<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use View,Input,Session,File,Hash,DB,Mail;
use Illuminate\Support\Facades\Crypt;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use Illuminate\Support\Facades\Validator;
use App;
use Log,PDF;
use PHPExcel; 
use PHPExcel_IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// use Hash;

use Libraries\SHAHasher;

use App\Models\User;

class IndexController extends Controller
{
	
	public function __construct()
    {
		
	}
	
	public function index()
    { 
		// Log::channel('custom')->info('Something happened!');
        return view('welcome');
    }
	
	public function pdf()
    { 
		$pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML('<h1>Test</h1>');
		return $pdf->stream();
    }
	
	public function excel()
    {  
		$array = [array("nama" => "asd" , "alias" => "asd" ,  "lahir" => "asd" ,  "negara" => "asd" ,  "alamat" => "asd" ,   "keterangan" => "asd" , )];
		$fileName = 'export';            
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0); 
        $objPHPExcel->getActiveSheet()
                    ->setCellValue('A1', 'NAMA')
                    ->setCellValue('B1', 'NAMA ALIAS')
                    ->setCellValue('C1', 'TAMPAT TANGGAL LAHIR')
                    ->setCellValue('D1', 'NEGARA')
                    ->setCellValue('E1', 'ALAMAT')
                    ->setCellValue('F1', 'KETERANGAN')
                    ;

        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);  
        $no=1;
        $row=2; 
        //Put each record in a new cell
        foreach ($array as $a){ 
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $a['nama']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $a['alias']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $a['lahir']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $a['negara']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $a['alamat']);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $a['keterangan']);
        $no++; 
        $row++;   
        }
         
        $objPHPExcel->getActiveSheet()->setTitle('Sheet1'); 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '.xls"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); 
    }
	
}