@extends('sub_admin.master')
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
           
            <form method="post" action="/product_edit_sub" enctype="multipart/form-data" class="forms-sample" >
              @csrf
              <input type="hidden" id="userId" name="userId" value="{{ session('userId') }}">
              <input type="hidden" id="product_id" name="product_id" value="{{ $get_product->id }}">
              
              <div class="form-group">
                <label for="exampleInputUsername1">Product Name</label>
                <input type="text" class="form-control" id="productName" value="{{ $get_product->name }}" name="productName" onkeyup="return name1();" placeholder="Enter product name">
                <span id="p_name_error" style="color: red;"></span>
              </div>
              <div class="form-group">
                <label for="exampleTextarea1">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" onkeyup="return desc();" placeholder="Enter description">{{ $get_product->description }}</textarea>
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
                  <input type="text" class="form-control" id="amount" name="amount" value="{{ $get_product->price }}" onkeyup="return price();" aria-label="Amount (to the nearest dollar)" placeholder="Enter amount">
                  
                </div>
                <span id="p_price_error" style="color: red;"></span>
                <br>
                <label for="images">Old Image</label>
                <p> remove images and upload new images</p>
                @foreach ($get_product_images as $item)
                    <div class="image-container">
                        <img src="{{ asset($item->path) }}" width="20%;" height="60px;" alt="edit image">
                        <input type="hidden" name="old_images[]" value="{{ $item->path }}" multiple>
                        <button class="remove-image" data-image-id="{{ $item->id }}">Remove</button>
                    </div>
                @endforeach
                
                <div class="form-group" style="display: none;" id="upload">
                    <label for="images">File upload</label>
                    <input type="file" name="images[]" id="images" class="form-control" multiple>
                    <span id="p_image_error" style="color: red;"></span>
                </div>
                
                <input type="hidden" name="total_image_count" id="total_image_count" value="{{ count($get_product_images) }}">
                <input type="hidden" id="max_image_count" value="{{ count($get_product_images) }}">
                
                <script>
                    const removeButtons = document.querySelectorAll('.remove-image');
                    const fileInput = document.getElementById('images');
                    const uploadForm = document.getElementById('upload');
                    const totalImageCountInput = document.getElementById('total_image_count');
                    const maxImageCountInput = document.getElementById('max_image_count');
                    const errorSpan = document.getElementById('p_image_error');
                
                    removeButtons.forEach(button => {
                        button.addEventListener('click', (event) => {
                            const imageId = button.getAttribute('data-image-id');
                            const imageContainer = button.closest('.image-container');
                            imageContainer.remove();
                            updateImageCounts();
                        });
                    });
                
                    fileInput.addEventListener('change', () => {
                        updateImageCounts();
                    });
                
                    function updateImageCounts() {
                        const remainingImages = document.querySelectorAll('.image-container');
                        const remainingImageCount = remainingImages.length;
                        const totalImageCount = remainingImageCount + fileInput.files.length;
                        totalImageCountInput.value = totalImageCount;
                
                        const maxAllowedImages = parseInt(maxImageCountInput.value, 10);
                        if (totalImageCount > maxAllowedImages) {
                            errorSpan.textContent = `Maximum ${maxAllowedImages} images allowed.`;
                            fileInput.value = '';
                            return false;
                        } else {
                            errorSpan.textContent = '';
                        }
                
                        if (remainingImageCount === 0) {
                            uploadForm.style.display = 'block';
                        } else {
                            uploadForm.style.display = 'none';
                        }
                    }
                
                    updateImageCounts();
                </script>
                  
                
                
                  {{-- onselect="return image();" --}}
               
                <br><br>
                  
             
             
             
             
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
    
    function mainValidation(){
      var p_name = document.getElementById('productName').value;
      var p_desc = document.getElementById('description').value;
      var p_price= document.getElementById('amount').value;
     
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
      }else{
         
         return true;
      }
    }
  </script>

@endsection