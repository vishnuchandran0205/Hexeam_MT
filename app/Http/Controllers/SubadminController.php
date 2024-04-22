<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Products;
use App\Models\Images;
use App\Models\User;

use Maatwebsite\Excel\Facades\Excel;

class SubadminController extends Controller
{
    public function sub_admin(){
        return view('sub_admin.dashboard');
    }
    public function add_product_form(Request $request){
        return view('sub_admin.add_products');
     }
  
     public function save_products(Request $request){
        $name=request('productName');
        $desc=request('description');
        $price=request('amount');
        $userId=request('userId');
        
        $imagePaths = [];
  
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/images', $imageName); 
    
               
                $imagePaths[] = 'storage/images/' . $imageName;
            }
        }
  
       $insertData= new Products();
       $insertData->name = $name;
       $insertData->description =$desc;
       $insertData->price = $price;
       $insertData->product_code = generateRandomString(6);
       $insertData->created_by = $userId;
  
       if($insertData->save()){
        $product_id= $insertData->id;
           foreach ($imagePaths as $path) {
              $insertImage = new Images();
              $insertImage->product_id = $product_id;
              $insertImage->path = $path;
              $insertImage->save();
           }
          return redirect()->back()->with('success', 'Product added successfully.');
  
       }else{
          return redirect()->back()->with('error', 'Insertion failed.');
       }
     }
  
     public function view_products(Request $request){
        $get_product = Products::select('products.id','products.name','products.description','products.price','products.product_code','products.product_status','products.created_by','users.user_type',
        Images::raw('GROUP_CONCAT(images.path) as multi_image')
       )
       ->join('images', 'images.product_id', '=', 'products.id')
       ->join('users','users.id','=','products.created_by')
       ->groupBy('products.id','products.name','products.description','products.price','products.product_code','products.product_status','products.created_by','users.user_type')
       ->paginate(5);
       // print_r($get_products);die;
        return view('sub_admin.view_product',compact('get_product'));
     }
  
  
     public function product_edit_form(Request $request, $id){
       // print_r($id);die;
       $get_product=Products::where('id',$id)->first();
       $get_product_images=Images::where('product_id',$id)->get();
       return view('sub_admin.edit_product_form', compact('get_product','get_product_images'));
     }
  
     public function update_products(Request $request){
        $name=request('productName');
        $desc=request('description');
        $price=request('amount');
        $userId=request('userId');
        $product_id=request('product_id');
        
        $imagePaths = [];
  
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/images', $imageName); 
    
               
                $imagePaths[] = 'storage/images/' . $imageName;
            }
        }else{
           $imagePaths[] =request('old_images');
        }
  
       $updateData=Products::where('id',$product_id)
       ->update([
           'name' => $name,
           'description' => $desc,
           'price' => $price,
           'updated_by' => $userId
       ]);
      
       if($updateData){
  
        $get_count_product_image = Images::select('id')->where('product_id', $product_id)->get();
  
       
        foreach ($get_count_product_image as $index => $image) {
            $newPath = $imagePaths[$index] ?? null; 
            if ($newPath !== null) {
                Images::where('id', $image->id)->update([
                    'path' => $newPath
                ]);
            }
        }
          return redirect('/view_products_sub')->with('success', 'Product added successfully.');
  
       }else{
          return redirect('/view_products_sub')->with('error', 'Insertion failed.');
       }
     }
  
     public function import_products(Request $request){
        $get_product=Products::get();
       
        return $get_product;
     }
  
     public function bulkDelete(Request $request){
        $productIds = $request->input('product_ids', []);
        $status=0;
        Products::whereIn('id', $productIds)->update([
           'product_status' => $status
        ]);
  
        return response()->json(['message' => 'Products deleted successfully']);
     }
  
     public function searchProducts(Request $request)
      {
          $productName = $request->input('productName');
          $status = $request->input('status');
          $date = $request->input('date');
         // dd($productName);
  
          $query =  Products::select('products.id','products.name','products.description','products.price','products.product_code','products.product_status','products.created_by','users.user_type',
          Images::raw('GROUP_CONCAT(images.path) as multi_image')
         )
         ->join('images', 'images.product_id', '=', 'products.id')
         ->join('users','users.id','=','products.created_by')
         ->groupBy('products.id','products.name','products.description','products.price','products.product_code','products.product_status','products.created_by','users.user_type');
         // dd($status);
          if ($productName) {
              $query->where('products.name', 'like', '%'.$productName.'%');
          }
  
          if ($status !== null && $status !== '') {
              $query->where('products.product_status', $status);
          }
      
          if ($date) {
              $query->whereDate('products.created_at', $date);
          }
  
          $products = $query->paginate(5);
         // dd($products);
  
          return $products;
      }
}
