<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WarehouseOwner extends Controller
{
    //login function
    public function Login(Request $request)
    {

        $filepath = 'C:\xampp\htdocs\programming\ownerinfo.json';
        $filecontent = file_get_contents($filepath);
        $jsoncontent = json_decode($filecontent, true);
        $username = $request->input('username');
        $phone = $request->input('phone number');
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
            $filepath = 'C:\Users\G.force\Desktop\Medicines.json';
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
    public function search()
    {
        $name = request();
        $category = request();
        $filepath = 'C:\Users\G.force\Desktop\Medicines.json';
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
    public function details()
    {
        $name = request();

        $filepath = 'C:\Users\G.force\Desktop\Medicines.json';
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
        $filepath = 'C:\Users\G.force\Desktop\Orders.json';
        $filecontent = file_get_contents($filepath);
        $jsoncontent = json_decode($filecontent, true);
        foreach ($jsoncontent as $item)
            return response()->json([$item]);
    }

    //orders management as modify
    public function ordermodify(Request $request)
    {
        $idx = request();
        $statue = $request->input('statue'); // bending or sent or recieved
        $paid = $request->input('paid'); // boolean variable
        // order modify
        $orderpath = 'C:\Users\G.force\Desktop\Orders.json';
        $orercontent = file_get_contents($orderpath);
        $jsonorder = json_decode($orercontent, true);
        $jsonorder[$idx]['statue']->$statue;
        $jsonorder[$idx]['paid']->$paid;

        //Medicine modiify
        $medicinepath = 'C:\Users\G.force\Desktop\Medicines.json';
        $medicinecontent = file_get_contents($medicinepath);
        $jsonmedicine = json_decode($medicinecontent, true);
        if ($statue = 'sent') {
            foreach ($jsonorder as $item) {
                // editing quantity of medicines in depot(Medicines.json) and (pharmacy.json)


            }
        }
    }
}
