<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WarehouseOwner extends Controller
{
    //login function
    public function Login(Request $request)
    {

        $filepath = 'C:\xampp\htdocs\my_website\Project_University\ownerinfo.json';
        $filecontent = file_get_contents($filepath);
        $jsoncontent = json_decode($filecontent, true);
        $username = $request->input('username');
        $phone_number = $request->input('phone_number');
        if(!$jsoncontent || !is_array($jsoncontent)){
            $info = [
                'username' => $username,
                'phone_number' => $phone_number,
            ];
            $content = [$info];
            file_put_contents($filepath , json_encode($content));
            return response()->json([
                'message' => 'successful login',
            ]);
        }
        else
        return response()->json([
            'message' => 'successful login',
            'homepage' => $jsoncontent
        ]);
    }

    //add product function
    public function add_product(Request $request)
    {
        $sc_name = $request->input('sc_name');
        $co_name = $request->input('co_name');
        $category = $request->input('category');
        $manufactor = $request->input('manufactor');
        $qty = $request->input('qty');
        $year = $request->input('year');
        $month = $request->input('month');
        $day = $request->input('day');
        $price = $request->input('price');

        if (!$sc_name  || !$co_name || !$category || !$manufactor || !$qty || !$year || !$month || !$day || !$price) {
            return response()->json([
                'message' => 'All fields are required'
            ]);
        } else {
            $filepath = 'C:\xampp\htdocs\my_website\Project_University\Medicines.json';
            $filecontent = file_get_contents($filepath);
            $jsoncontent = json_decode($filecontent, true);
            $medicine = [
                'sc_name' => $sc_name,
                'co_name' => $co_name,
                'category' => $category,
                'manufactor' => $manufactor,
                'qty' => $qty,
                'exp' => [
                    'year' => $year,
                    'month' => $month,
                    'day' => $day,
                ],
                'price' => $price,
            ];
            $exist = false;
            if (!$jsoncontent || !is_array($jsoncontent)) {
                $content = [$medicine];
                file_put_contents($filepath, json_encode($content));
                return response()->json([
                    'message' => 'Medicine added successfully'
                ]);
            } else {
                foreach ($jsoncontent as $item)
                    if ($item['sc_name'] == $sc_name) {
                        $exist = true;
                        $item['qty'] += $qty;
                        return response()->json([
                            'message' => 'Medicine is existed and modified successfully'
                        ]);
                    }
                if ($exist == false) {
                    $jsoncontent[] = $medicine;
                    file_put_contents($filepath, json_encode($jsoncontent));
                    return response()->json([
                        'message' => 'Medicine added successfully'
                    ]);
                }
            }
        }
    }

    //search function(by name and by category)
    public function search(Request $request)
    {
        $name = $request->input('sc_name');
        $category = $request->input('category');
        $filepath = 'C:\xampp\htdocs\my_website\Project_University\Medicines.json';
        $filecontent = file_get_contents($filepath);
        $jsoncontent = json_decode($filecontent, true);
        $exist = false;
        if ($name && ($category == null)) {
            foreach ($jsoncontent as $item) {
                if ($item['sc_name'] == $name) {
                    $exist = true;
                    return response()->json([
                        'message' => 'Medicine is existed',
                        'Medicine' => $item
                    ]);
                }
            }
            if ($exist == false) {
                return response([
                    'message' => 'Medicine is not existed'
                ]);
            }
        } else if ($category) {
            foreach ($jsoncontent as $item) {
                if ($item['category'] == $category) {
                    $exist = true;
                    return response()->json([
                        'message' => 'Medicine is existed',
                        'Medicine' => $item
                    ]);
                }
            }
            if ($exist == false) {
                return response([
                    'message' => 'Medicine is not existed'
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Bad request'
            ], 400);
        }
    }

    //Medicine details
    public function details(Request $request)
    {
        $name = $request->input('sc_name');

        $filepath = 'C:\xampp\htdocs\my_website\Project_University\Medicines.json';
        $filecontent = file_get_contents($filepath);
        $jsoncontent = json_decode($filecontent, true);
        $exist = false;

        foreach ($jsoncontent as $item) {
            if ($item['sc_name'] == $name) {
                $exist = true;
                return response()->json([
                    'message' => 'Medicine is existed',
                    'Medicine' => $item
                ]);
            }
        }
        if ($exist == false) {
            return response([
                'message' => 'Medicine is not existed'
            ]);
        }
    }

    //orders management as show
    public function orderes_show(Request $request)
    {
        $filepath = 'C:\xampp\htdocs\my_website\Project_University\Orders.json';
        $filecontent = file_get_contents($filepath);
        $jsoncontent = json_decode($filecontent, true);
        foreach ($jsoncontent as $item)
            return response()->json([$item]);
    }

    //orders management as modify
    public function ordermodify(Request $request)
    {
        $idx = $request->input('idx');
        $statue = $request->input('statue'); // bending or sent or recieved
        $paid = $request->input('paid'); // boolean variable
        // order modify
        $orderpath = 'C:\xampp\htdocs\my_website\Project_University\Orders.json';
        $orercontent = file_get_contents($orderpath);
        $jsonorder = json_decode($orercontent, true);
        $jsonorder[$idx]['statue'] = $statue;
        $jsonorder[$idx]['paid'] = $paid;

        //Medicine modiify
        $medicinepath = 'C:\xampp\htdocs\my_website\Project_University\Medicines.json';
        $medicinecontent = file_get_contents($medicinepath);
        $jsonmedicine = json_decode($medicinecontent, true);
        //pharmacy modify
        $pharmacypath = 'C:\xampp\htdocs\my_website\Project_University\pharmacy.json';
        $pharmacycontent = file_get_contents($pharmacypath);
        $jsonpharmacy = json_decode($pharmacycontent , true);
        // modifying is here
        $found = false ;
        if ($statue = 'sent') {
            for($i = 0 ; $i < count($jsonorder[$idx]['med']) ; $i++){
                foreach($jsonmedicine as $medicine)
                    if($medicine['sc_name'] == $jsonorder[$idx]['med']['sc_name[$i]']){
                        $medicine['qty'] -= $jsonorder[$idx]['med']['qty[$i]'] ;
                        file_put_contents($medicinepath , json_encode($medicine));
                    }
                    foreach($jsonpharmacy as $med){
                        if($med['medicine']->scientific_name == $jsonorder[$idx]['med']['sc_name[$i]']){
                            $found = true ;
                            $med['medicine']->quantity += $jsonorder[$idx]['med']['qty[$i]'];
                            file_put_contents($pharmacypath , json_encode($med));
                        }

                    }
                    if($found == false){
                        $new = [
                            'scientific_name' => $jsonorder[$idx]['med']['sc_name[$i]'],
                            'quantity' => $jsonorder[$idx]['med']['qty[$i]'],
                        ];
                        file_put_contents($pharmacypath , json_encode($new));
                        $found = false ;
                    }
                }

            }

    }
}
