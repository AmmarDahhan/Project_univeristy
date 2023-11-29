<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Pharmacy extends Controller
{
    //
    public function Register(Request $request)
    {
        $filepath = 'C:\Users\G.force\Desktop\pharmacy.json';
        $flecontent = file_get_contents($filepath);
        $jsoncontent = json_decode($flecontent, true);
        $username = $request->input('username');
        $phone = $request->input('phone');
        $password = $request->input('password');
        $password = Hash::make($password);

        if (!$username || !$phone || !$password) {
            return response()->json([
                'message' => 'All fields are required'
            ], 400);
        }
        if (!$jsoncontent || !is_array($jsoncontent))
            $id = 1;
        else if ($jsoncontent) {
            $id = count($jsoncontent) + 1;
        }


        $info = [
            'id' => $id,
            'username' => $username,
            'phone' => $phone,
            'password' => $password,
            'medicine' => [
                'scientific_name' => null,
                'commercial_name' => null,
                'category' => null,
                'Manufactor' => null,
                'quantity' => null,
                'exp_date' => [

                    'year' => null,
                    'month' => null,
                    'day' => null

                ],
                'price' => null,
            ],
        ];
        $exist = false;
        if (!$jsoncontent || !is_array($jsoncontent)) {
            $content = [$info];
            file_put_contents($filepath, json_encode($content));
            return response()->json([
                'message' => 'Register made successfully'
            ]);
        } else {
            foreach ($jsoncontent as $item)
                if ($phone == $item['phone']) {
                    $exist = true;
                    return response()->json([
                        'message' => 'user is existed'
                    ]);
                }
            if ($exist == false) {
                $jsoncontent[] = $info;
                file_put_contents($filepath, json_encode($jsoncontent));
                return response()->json([
                    'message' => 'Register made successfully'
                ]);
            }
        }
    }
    //traverse throught medicine in depot refering to its category
    public function traverse()
    {
        $filepath = 'C:\Users\G.force\Desktop\Medicines.json'; // path of pharmacy file .json
        $filecontent = file_get_contents($filepath);
        $jsoncontent = json_decode($filecontent, true);
        // sort by category
        $jsoncontent = collect($jsoncontent)->sortBy('category', SORT_REGULAR, true);

        return response()->json([
            $jsoncontent
        ]);
    }

    //order from depot
    public function order(Request $request)
    {
        $pharmacy = $request->input('pharmacy');
        $sc_name[] = $request->input('sc_name');
        $qty[] = $request->input('qty');

        // Medicine in depot path
        $Medicinepath = 'C:\Users\G.force\Desktop\Medicines.json';
        $Medicinecontent = file_get_contents($Medicinepath);
        $jsonMedicine = json_decode($Medicinecontent, true);

        // orders path
        $orderpath = 'C:\Users\G.force\Desktop\Orders.json';
        $ordercontent = file_get_contents($orderpath);
        $jsonorder = json_decode($ordercontent, true);
        // adding order

        $exist = false;
        if (!$sc_name || !$qty) {
            return response()->json([
                'message' => 'All fields are required'
            ]);
        } else {
            for ($i = 0; $i < count($sc_name); $i++) {
                foreach ($jsonMedicine as $item) {
                    if ($item['sc_name'] == $sc_name[$i]) {
                        $exist = true;
                        if ($item['qty'] < $qty[$i]) {
                            return response()->json([
                                'message' => 'Quantity you are asking for is more than existed'
                            ]);
                        }
                    } else {
                        return response()->json([
                            'message' => sprintf('%s This medicine is not existed', $sc_name[$i])
                        ]);
                    }
                }
            }
            $total_price = 0;
            for ($i = 0; $i < count($sc_name); $i++) {
                foreach ($jsonMedicine as $item) {
                    if ($item['sc_name'] == $sc_name[$i]) {
                        $total_price += ($item['price'] * $qty[$i]);
                    }
                }
            }
            $info = [
                'pharmacy' => $pharmacy,
                'statue' => 'bending',
                'paid' => false,
                'med' => [
                    'sc_name' => $sc_name,
                    'qty' => $qty
                ],
                'total_price' => $total_price,
            ];

            if (!$ordercontent || !is_array($ordercontent)) {
                $content = [$info];
                file_put_contents($orderpath, json_encode($content));
                return response()->json([
                    'message' => 'order added successfully'
                ]);
            } else {
                $ordercontent[] = $info;
                file_put_contents($orderpath, json_encode($ordercontent));
                return response()->json([
                    'message' => 'order added successfully'
                ]);
            }
        }
    }
    //show orders for pharmacist
    public function show_orders(){
        $pharmacist = request();
    // orders path
        $orderpath = 'C:\Users\G.force\Desktop\Orders.json' ;
        $ordercontent = file_get_contents($orderpath);
        $jsonorder = json_decode($ordercontent , true);
    // orders show
        $exist = false ;
        if(!$jsonorder || !is_array($jsonorder)){
            return response()->json([
                'message' => 'No orders found'
            ]);
        }
        foreach($jsonorder as $item)
            if($item['pharmacist' ] == $pharmacist){
                $exist = true ;
                return response()->json([ $item ]);
        }
        if($exist == false)
            return response()->json([
                'message' => 'No orders found'
            ]);

    }
    // Medicine details
    public function details(){
        $name = request();

        $filepath = 'C:\Users\G.force\Desktop\Medicines.json';
        $filecontent = file_get_contents($filepath);
        $jsoncontent = json_decode($filecontent , true);
        $exist = false ;

            foreach($jsoncontent as $item){
                if($item['sc_name'] == $name){
                    $exist = true ;
                    return response()->json([
                        'message' => 'Medicine is existed',
                        'Medicine' => $item
                    ]);
                }
            }
            if($exist == false){
                return response([
                    'message' => 'Medicine is not existed'
                ]);
            }
    }

    //search function(by name and by category)
    public function search(Request $request){
        $name = $request->input('sc_name');
        $category = $request->input('category');

        $filepath = 'C:\Users\G.force\Desktop\Medicines.json';
        $filecontent = file_get_contents($filepath);
        $jsoncontent = json_decode($filecontent , true);
        $exist = false ;
        if($name && ($category == null)){
            foreach($jsoncontent as $item){
                if($item['sc_name'] == $name){
                    $exist = true ;
                    return response()->json([
                        'message' => 'Medicine is existed',
                        'Medicine' => $item
                    ]);
                }
            }
            if($exist == false){
                return response([
                    'message' => 'Medicine is not existed'
                ]);
            }
        }
        else if($category){
            foreach($jsoncontent as $item){
                if($item['category'] == $category){
                    $exist = true ;
                    return response()->json([
                        'message' => 'Medicine is existed',
                        'Medicine' => $item
                    ]);
                }
            }
            if($exist == false){
                return response([
                    'message' => 'Medicine is not existed'
                ]);
            }
        }
        else{
            return response()->json([
                'message' => 'Bad request'
            ] , 400);
        }
    }

}
//this function returns one medicine only
