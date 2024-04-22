<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Products;
use App\Models\Images;
use App\Models\User;

class ApiProductController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum')->only('get_products');
    // }

    public function get_products(Request $request){

        $products=Products::select('products.id','products.name','products.description','products.price','products.product_code','products.product_status','products.created_by',
        Images::raw('GROUP_CONCAT(images.path) as multi_image')
       )
       ->join('images', 'images.product_id', '=', 'products.id')
       ->groupBy('products.id','products.name','products.description','products.price','products.product_code','products.product_status','products.created_by')
       ->get();

       if ($products->isEmpty()) {
                return response()->json([
                    'responseMsg' => 'No data found.',
                    'responseCode' => 404,
                    'responseStatus' => false
                ]);
        }else{

            return response()->json([
                'responseMsg' => 'Product Listing.',
                'responseCode' => 200,
                'responseStatus' => true,
                'data' => $products
            ]);
        }

    }
    
}
