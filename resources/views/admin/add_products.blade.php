@extends('admin.master')
@section('content')

<div class="content-wrapper">
    <div class="row">
      <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Add Products</h4>
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
           
            <form method="post" action="/save_products" enctype="multipart/form-data" class="forms-sample" >
              @csrf
              <input type="hidden" id="userId" name="userId" value="{{ session('userId') }}">

              <div class="form-group">
                <label for="exampleInputUsername1">Product Name</label>
                <input type="text" class="form-control" id="productName" name="productName" onkeyup="return name1();" placeholder="Enter product name">
                <span id="p_name_error" style="color: red;"></span>
              </div>
              <div class="form-group">
                <label for="exampleTextarea1">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" onkeyup="return desc();" placeholder="Enter description"></textarea>
                <span id="p_desc_error" style="color: red;"></span>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Product Price</span>
                  </div>
                  <div class="input-group-prepend">
                    <span class="input-group-text">0.00</span>
                  </div>
                  <input type="text" class="form-control" id="amount" name="amount" onkeyup="return price();" aria-label="Amount (to the nearest dollar)" placeholder="Enter amount">
                  
                </div>
                <span id="p_price_error" style="color: red;"></span>

                <div class="form-group">
                  <label for="images">File upload</label>
                  <input type="file" name="images[]" id="images" class="form-control" multiple>
                  <span id="p_image_error" style="color: red;"></span>
              </div>
                  {{-- onselect="return image();" --}}
               
                <br>
             
              <button type="submit" onclick="return mainValidation();" class="btn btn-primary mr-2">Submit</button>

            </form>
          </div>
        </div>
      </div>
     
    </div>
  </div>

  <script>
    function name1(){
      var p_name = document.getElementById('productName').value;
    
      if(p_name == "" || p_name == null){
         document.getElementById('p_name_error').innerHTML = 'This field is required.';
         return false;
      }else{
        document.getElementById('p_name_error').innerHTML = '';
         return true;
      }
    }
    function desc(){
     
      var p_desc = document.getElementById('description').value;
     
      if(p_desc == "" || p_desc == null){
         document.getElementById('p_desc_error').innerHTML = 'This field is required.';
         return false;
      }else{
        document.getElementById('p_desc_error').innerHTML = '';
         return true;
      }
    }
    function price(){
     
      var p_price= document.getElementById('amount').value;
      var priceRegex = /^\d+(\.\d{1,2})?$/;
      if(p_price == "" || p_price == null){
         document.getElementById('p_price_error').innerHTML = 'This field is required.';
         return false;
      }else if(!priceRegex.test(p_price.trim())) {
        document.getElementById('p_price_error').innerHTML = 'Price must be a valid price format.';
        return false;
      }else{
        document.getElementById('p_price_error').innerHTML = '';
         return true;
      }
    }
    function image(){
      
      var p_image= document.getElementById('images').value;
      if(p_image == "" || p_image == null){
         document.getElementById('p_image_error').innerHTML = 'This field is required.';
         return false;
      }else{
        document.getElementById('p_image_error').innerHTML = '';
         return true;
      }
    }
    function mainValidation(){
      var p_name = document.getElementById('productName').value;
      var p_desc = document.getElementById('description').value;
      var p_price= document.getElementById('amount').value;
      var p_image= document.getElementById('images').value;
      var priceRegex = /^\d+(\.\d{1,2})?$/;
      if(p_name == "" || p_name == null){
         document.getElementById('p_name_error').innerHTML = 'This field is required.';
         return false;
      }else if(p_desc == "" || p_desc == null){
         document.getElementById('p_desc_error').innerHTML = 'This field is required.';
         return false;
      }else if(p_price == "" || p_price == null){
         document.getElementById('p_price_error').innerHTML = 'This field is required.';
         return false;
      }else if(!priceRegex.test(p_price.trim())) {
        document.getElementById('p_price_error').innerHTML = 'Price must be a valid price format.';
        return false;
      }else if(p_image == "" || p_image == null){
         document.getElementById('p_image_error').innerHTML = 'This field is required.';
         return false;
      }else{
         
         return true;
      }
    }
  </script>
    
@endsection