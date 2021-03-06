<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use View,Input,Session,File,Hash,DB,Mail,Redirect,Auth;
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
use Illuminate\Contracts\Encryption\DecryptException;


use App\Models\User;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->data['datas']    = [];
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home')->with($this->data);
    }

    public function mapsMatrix(Request $request){
        $valid = $this->validate($request, [
            'avoid' => 'required',
            'file'  => 'required|mimes:xlsx,xls',
        ]);

        $return = [];
        if(!$valid){
            return Redirect::back()->withErrors(['The file must be a file of type: xlsx, xls']);
        }else{

            $inputFileName = $request->file('file')->getRealPath();
            try{
                $spreadsheet = PHPExcel_IOFactory::load($inputFileName);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows   = [];
                $x      = 0;
                foreach ($worksheet->getRowIterator() AS $row) {
                    
                    if($x > 0){
                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                        $cells = [];
                        foreach ($cellIterator as $cell) {
                            $cells[] = $cell->getValue();
                        }
                        $rows[] = ["type" => $cells[0], "desc" => $cells[1] , "coordinates" => str_replace(' ', '',$cells[2])];

                    }
                    $x++;
                }
                
            }catch(Exception $e){
                return Redirect::back()->withErrors(["Error file ".$e->getMessage()]);
            }


            if(count($rows) > 0){
               $return  = $this->distanceMatrix($rows,$request->avoid);
            }
         
            usort($return, function($a, $b) {
                return $b['distance_value'] <=> $a['distance_value'];
            });


            $maps   = [];
            $x      = 0;
            foreach($return as $data){
                $fromCoordinates    =  explode(',', $data["from"]["coordinates"]);
                $toCoordinates      =  explode(',', $data["to"]["coordinates"]);
                if($x <=0){
                    $maps[] =  [$data["from"]["desc"] , floatval($fromCoordinates[0]) , floatval($fromCoordinates[1]) , 1];
                    $maps[] =  [$data["from"]["desc"] , floatval($toCoordinates[0]) , floatval($toCoordinates[1]) , 2];                    $x=2;
                }else{
                    $maps[] =  [$data["from"]["desc"] , floatval($toCoordinates[0]) , floatval($toCoordinates[1]) , 2];
                    // $maps[] =  ["type" => "Feature" , "geometry" => [ "type" => "Point", "coordinates" => array_map('floatval', explode(',', $data["to"]["coordinates"]))], "properties" => [ "prop0" => "value".$x]];
                }
                $x++;
            }
            
            // echo json_encode($datasMaps);
            $this->data['datas']    = $return;
            $this->data['avoid']    = $request->avoid;
            $this->data['maps']     = $maps;
            return view('home')->with($this->data);
        }

    }


    private function distanceMatrix($datas,$avoid){
        $rows   = [];
        $header = [];
        $x      = 0;
        foreach($datas as $data){
            if($data["type"] == "MID"){
                $header = $data;
            }else{
                $google     = $this->googleMaps($header["coordinates"] , $data["coordinates"],$avoid);
                $rows[]     = ["from" => $header, "to" => $data, "origin_addresses" => @$google["origin_addresses"][0] , "distance_value" => @$google["rows"][0]["elements"][0]["distance"]["value"] , "distance_text" => @$google["rows"][0]["elements"][0]["distance"]["text"]  , "duration_value" => @$google["rows"][0]["elements"][0]["duration"]["value"]   , "duration_text" => @$google["rows"][0]["elements"][0]["duration"]["text"] ];
            }
            $x++;
            usleep(25000);
        }

        return $rows;
    }


    private function googleMaps($origin,$destination,$avoid){
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$origin&destinations=$destination&avoid=$avoid&key=AIzaSyDKsEC_DDuCMdWKPgN4dNQVKxPjte6M_Rc";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response, true);

        return $response_a;
    }



    public function profile()
    {
        $this->data["user"] = User::findOrFail(Auth::user()->user_id);
        return view('profile')->with($this->data);
    }

    public function profilePost(Request $request){
        $cek = User::findOrFail(Auth::user()->user_id);
        $valid = $this->validate($request, [
            'name'          => 'required|max:255',
            'phone'         => 'max:20',
            'new_password'  => 'max:255',
        ]);
        
        if($request->new_password){
            $edit   = ['name' => $request->name, 'phone' => $request->phone ,'password' => Hash::make($request->new_password)];
        }else{
            $edit   = ['name' => $request->name, 'phone' => $request->phone];
        }
        $cek->update($edit); 
        return redirect()->back()->with('success', 'Update Successfully');
    }


    public function users(Request $request)
    {
        $search = $request->filter;
        if(Auth::user()->type == "root"){
            $query = User::whereNotIn('user_id',[Auth::user()->user_id])->orderBy('user_id','DESC');
            if ($search) {
                $like = "%{$search}%";
                $query = $query->where('email', 'LIKE', $like)->orWhere('name', 'LIKE', $like);
            }
            $this->data["users"] = $query->paginate(10);
            return view('users')->with($this->data);

        }else{
            return redirect()->back()->with('error', 'Unauthorized');
        }
    }


    public function userDetailPost(Request $request , $encrpt)
    {
        try {
            $decrypted = Crypt::decryptString($encrpt);
            if(Auth::user()->type == "root"){
                $valid = $this->validate($request, [
                    'name'          => 'required|max:255',
                    'phone'         => 'max:20',
                    'email'         => 'required|max:255|unique:users,email,'.$decrypted.',user_id',
                    'new_password'  => 'max:255',
                ]);
                
                    if($request->new_password){
                        $edit   = ['email' => $request->email, 'name' => $request->name, 'phone' => $request->phone ,'password' => Hash::make($request->new_password)];
                    }else{
                        $edit   = ['email' => $request->email, 'name' => $request->name, 'phone' => $request->phone];
                    }
                User::where('user_id', $decrypted)->update($edit);

                return redirect()->back()->with('success', 'Update Successfully');
            }else{
                return redirect()->back()->with('error', 'Unauthorized');
            }
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', $e);
        }
    }

    public function userDetail($encrpt)
    {
        try {
            $decrypted = Crypt::decryptString($encrpt);
            if(Auth::user()->type == "root"){
                $this->data["encrpt"]   = $encrpt;
                $this->data["user"]     = User::findOrFail($decrypted);
                return view('userDetail')->with($this->data);
            }else{
                return redirect()->back()->with('error', 'Unauthorized');
            }
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', $e);
        }
    }
}
