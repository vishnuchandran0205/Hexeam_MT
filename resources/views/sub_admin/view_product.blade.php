@extends('sub_admin.master')
@section('content')
<script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Products Details</h4>
        <button style="text-align: left;" type="button" onclick="return exportToExcel();" class="btn btn-primary">Import Products</button>
        <button style="text-align: left;" type="button" onclick="bulkDeleteProducts()" class="btn btn-danger">Bulk Delete Products</button>
        <script>
          function exportToExcel() {
          
              $.ajax({
                  type: 'get',
                  url: '/import_products_sub',
                  data: {
                      _token: '{{ csrf_token() }}'
                  },
                  success: function (data) {
                      console.log(data);
                     
                      if (data.length > 0) {
                        var worksheet = XLSX.utils.json_to_sheet(data);
                        var workbook = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet1"); 

                        var filename = "Bulk_Imported_Products.xlsx";
                        
                        try {
                            XLSX.writeFile(workbook, filename);
                        } catch (e) {
                         
                        }
                      } else {
                      
                    }
                        alert('Import successful!');
                     
                  },
                  error: function (xhr, status, error) {
                      console.error('Error: ' + error);
                   
                  }
              });
          }
      </script>
       <script>
        function bulkDeleteProducts() {
            var selectedProductIds = [];
            $('input[name="selectedProducts"]:checked').each(function() {
                selectedProductIds.push($(this).val());
            });

            if (selectedProductIds.length === 0) {
                alert('Please select at least one product to delete.');
                return;
            }

          

            $.ajax({
                type: 'POST',
                url: '/bulk_delete_products_sub',
                data: {
                    _token: '{{ csrf_token() }}',
                    product_ids: selectedProductIds
                },
                success: function (response) {
                   
                    alert('Selected products deleted(inactive) successfully.');
                    location.reload(); 
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }
      </script>
      <br>
      <div class="row">
        <div class="col-md-2">
            <input type="text" class="form-control" id="se_productName"  placeholder="Enter product name">
        </div>
        <div class="col-md-2">
          <select class="form-control" id="se_status" name="se_status">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
        </div>
        <div class="col-md-2">
          <input type="date" class="form-control" id="date"  >
      </div>
      <div class="col-md-2">
        <button style="background-color:blue;color:aliceblue" type="button" onclick="return searchDatata();" class="btn btn-primery">Search</button>
    </div>
    </div>
   

        <div class="table-responsive">
          <table class="table" >
            <thead>
              <tr>
                <th></th>
                <th>slno</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Product Code</th>
                <th>Image</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
                @if (isset($get_product))
                @php
                   $i=1; 
                @endphp
                @foreach ($get_product as $item)

                <tr>
                  <td>
                    @if ($item->user_type == 1)
                     
                    @elseif ($item->user_type == 2)
                        <input type="checkbox" name="selectedProducts" value="{{ $item->id }}">
                    @endif
                </td>
                    <td>{{ $i++; }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->product_code }}</td>
                    <td>
                        @php
                        $imagePaths = explode(',', $item->multi_image);
                    @endphp
                    @foreach ($imagePaths as $imagePath)
                        <img src="{{ asset($imagePath) }}" alt="Product Image">
                    @endforeach
                    
                    </td>
                    <td>
                      @if($item->product_status==1)
                      Active
                      @elseif ($item->product_status == 0)
                      Inactive
                      @endif

                    </td>
                    <td>
                      @if ($item->user_type == 1)
                     
                      @elseif ($item->user_type == 2)
                      <a href="/product_edit_form_sub{{$item->id}}">
                        <button type="button" style="background: blue; color:white;" class="btn btn-primary mr-2">Edit</button>
                      </a>
                      @endif
                     
                      
                    </td>
               
                </tr>
                    
                @endforeach
                    
                @endif
             
            </tbody>
            
          </table>
         
        
        </div>

        <tfoot>
          <tr>
              <td colspan="8">
                  <div class="pagination justify-content-center">
                      <div class="at-pagination">
                          {{ $get_product->onEachSide(2)->links('pagination::bootstrap-4') }}
                      </div>
                  </div>
              </td>
          </tr>
      </tfoot>
       
      </div>
    </div>
  </div>

  <script>
    function searchDatata() {
      var productName = document.getElementById('se_productName').value;
      var status = document.getElementById('se_status').value;
      var date = document.getElementById('date').value;
  
      $.ajax({
        type: 'get',
        url: '/search_products_sub',
        data: {
          _token: '{{ csrf_token() }}',
          productName: productName,
          status: status,
          date: date
        },
        success: function(response) {
          console.log('res:',response);
          $('table tbody').empty();
  
          if (response && response.data && Array.isArray(response.data)) {
            $.each(response.data, function(index, item) {
              console.log('res:',response.data);
              addRowToTable(item);
            });
          } else {
            console.error('Invalid response format:', response);
          }
        },
        error: function(xhr, status, error) {
          console.error('Error:', error);
          alert('An error occurred. Please try again.');
        }
      });
    }
  
    function addRowToTable(item) {
      var imagePaths = item.multi_image.split(',');
      var imagesHtml = '';
      $.each(imagePaths, function(idx, imagePath) {
        imagesHtml += '<img src="' + imagePath + '" alt="Product Image">';
      });
  
      var statusText = item.product_status == 1 ? 'Active' : 'Inactive';
     console.log('status:',statusText);
     var i = 1;
     $('table tbody').append(
      '<tr>' +
        (item.user_type == 2 ?
          '<td><input type="checkbox" name="selectedProducts" value="' + item.id + '"></td>' :
          '') + 

        '<td>' + (i++) + '</td>' +
        '<td>' + item.name + '</td>' +
        '<td>' + item.description + '</td>' +
        '<td>' + item.price + '</td>' +
        '<td>' + item.product_code + '</td>' +
        '<td>' + imagesHtml + '</td>' +
        '<td>' + statusText + '</td>' +
        '<td>' +
          (item.user_type == 2 ?
            '<a href="/product_edit_form/' + item.id + '">' +
              '<button type="button" style="background: blue; color:white;" class="btn btn-primary mr-2">Edit</button>' +
            '</a>' :
            '') + 

        '</td>' +
      '</tr>'
    );
    }
</script>
    
@endsection