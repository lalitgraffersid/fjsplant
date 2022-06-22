@extends('admin.layout.master')
@section('content')
 
<style type="text/css">
   .select12:invalid + .select2 .select2-selection{
       border-color: #dc3545!important;
   }
   .select12:valid + .select2 .select2-selection{
       border-color: #28a745!important;
   }
   *:focus{
     outline:0px;
   }
</style>

<style>
   .custom_close{
     position: relative;
    display: inline-block;
   }
  .custom_close button{
   position: absolute;
    right: 0;
    width: 25px;
    height: 25px;
    line-height: 0;
    text-align: center;
    padding: 0;
    font-size: 10px !important;
}
</style>

<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Product</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                  <li class="breadcrumb-item active">Product Edit</li>
               </ol>
            </div>
         </div>
      </div>
   </div>

   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-12">
               <div class="card card-primary">
                  <div class="card-header">
                     <h3 class="card-title">Edit <small>Product</small></h3>
                  </div>
                  @if (count($errors) > 0)       
                     <div class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        @foreach ($errors->all() as $error)
                           <span>{{ $error }}</span><br/>
                        @endforeach    
                     </div>         
                  @endif
                  <form id="quickForm" action="{{route('products.update')}}" method="POST" enctype="multipart/form-data" >
                     {{csrf_field()}}
                     <input type="hidden" name="id" value="{{ $result->id }}">

                     <div class="card-body">

                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="category_id">Category</label>
                                 <select name="category_id" class="category_id select12 form-control" id="category_id" data-placeholder="Select a category" style="width: 100%;" required >
                                    <option value="">Select Category</option>
                                    @foreach($categories as $value)
                                       <option value="{{$value->id}}" <?php if($result->category_id == $value->id){echo "selected";} ?> >{{$value->name}}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>

                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="dealer_id">Dealer/Brand/Make</label>
                                 <select name="dealer_id" class="dealer_id select12 form-control" id="dealer_id" data-placeholder="Select a Dealer" style="width: 100%;" required>
                                    <option value="">Select Dealer</option>
                                    @foreach($dealers as $value)
                                       <option value="{{$value->id}}" <?php if($result->dealer_id == $value->id){echo "selected";} ?> >{{$value->name}}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                        </div>

                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="stock_number">Serial Number</label>
                                 <input type="text" name="stock_number" class="form-control" id="stock_number" value="{{ $result->stock_number }}" placeholder="Serial Number">
                              </div>
                           </div>

                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="backorder_number">Backorder Number</label>
                                 <input type="text" name="backorder_number" class="form-control" id="backorder_number" value="{{ $result->backorder_number }}" placeholder="Backorder Number">
                              </div>
                           </div>
                        </div>

                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="title">Title</label>
                                 <input type="text" name="title" value="{{ $result->title }}" class="form-control" id="title" placeholder="Title">
                              </div>
                           </div>

                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="date">Date</label>
                                 <input type="date" name="date" value="{{ $result->date }}" class="form-control" id="date" >
                              </div>
                           </div>
                        </div>

                        <div class="row">
                           <div class="col-md-3">
                              <div class="form-group">
                                 <label for="model">Model</label>
                                 <input type="text" name="model" value="{{ $result->model }}" class="form-control" id="model" placeholder="Model">
                              </div>
                           </div>

                           <div class="col-md-3">
                              <div class="form-group">
                                 <label for="year">Year</label>
                                 <input type="text" name="year" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy" data-mask id="year" value="{{ $result->year }}">
                              </div>
                           </div>

                           <div class="col-md-3">
                              <div class="form-group">
                                 <label for="hours">Hours</label>
                                 <input type="number" name="hours" class="form-control" id="hours" placeholder="Hours" value="{{ $result->hours }}">
                              </div>
                           </div>

                           <div class="col-md-3">
                              <div class="form-group">
                                 <label for="weight">Weight</label>
                                 <input type="number" name="weight" step="0.02" class="form-control" id="weight" placeholder="Weight" value="{{ $result->weight }}">
                              </div>
                           </div>
                        </div>

                        <div class="form-group">
                           <label for="description">Description</label>
                           <textarea name="description" id="description" >{{ $result->description }}</textarea>
                        </div>

                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="price">Price</label>
                                 <input type="number" name="price" class="form-control" id="price" placeholder="Price" step="0.02" value="{{ $result->price }}">
                              </div>
                           </div>

                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="type">Type</label>
                                 <select name="type" class="type form-control" id="type" data-placeholder="Select a type" style="width: 100%;" required>
                                    <option value="">Select Type</option>
                                    <option value="New" <?php if($result->type == 'New'){echo "selected";} ?>>New</option>
                                    <option value="Used" <?php if($result->type == 'Used'){echo "selected";} ?>>Used</option>
                                 </select>
                              </div>
                           </div>
                        </div>

                        <div class="row">
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label for="status">Status</label>
                                 <select name="status" class="status form-control" id="status" data-placeholder="Select status" style="width: 100%;" required>
                                    <option value="">Select status</option>
                                    <option value="Coming Soon" <?php if($result->status == 'Coming Soon'){echo "selected";} ?>>Coming Soon</option>
                                    <option value="In Stock" <?php if($result->status == 'In Stock'){echo "selected";} ?>>In Stock</option>
                                    <option value="Sold" <?php if($result->status == 'Sold'){echo "selected";} ?>>Sold</option>
                                 </select>
                              </div>
                           </div>

                           <div class="col-md-4" id="upcoming_quantity_div">
                              <div class="form-group">
                                 <label for="upcoming_quantity">Upcoming Quantity</label>
                                 <input type="number" name="upcoming_quantity" value="{{ $result->upcoming_quantity }}" class="form-control" min="0" id="upcoming_quantity" >
                              </div>
                           </div>

                           <div class="col-md-4">
                              <div class="form-group">
                                 <label for="date">Date</label>
                                 <input type="date" name="date" value="{{ $result->date }}" class="form-control" id="date" >
                              </div>
                           </div>

                        </div>

                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="image">Product Images</label>
                                 <input type="file" name="image[]" class="form-control" id="image" accept="image/*" multiple>
                              </div>
                              @if (count($productImages)>0)
                                <br>
                                @foreach($productImages as $productImage)
                                 <div class="custom_close">
                                    <img src="{{ asset('/public/admin/clip-one/assets/products/thumbnail')}}/{{ $productImage->image }}" alt="" class="product-edit-img"> 
                                      <button type="button" class="btn btn-danger product-edit-btn" id="delete_img" data-id="{{$productImage->id}}"><i class="far fa-trash-alt"></i></button>
                                 </div>
                                @endforeach
                              @endif
                           </div>

                           <?php 
                           if (!empty($result->attachment)) {
                              $string = Str::slug($result->attachment, '.');
                              $array1 = preg_split ("/\./", $string);
                              $ext = end($array1);
                           }else{
                              $ext = '';
                           }

                           ?>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="attachment">Attachment</label>
                                 <input type="file" name="attachment" class="form-control" id="attachment" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,text/plain, application/pdf" ><br>
                                 <?php if (!empty($result->attachment)){ ?>
                                    <i class="far fa-file-{{$icons[$ext]}} fa-5x text-center"/></i>
                                    <a class="" href="{{ asset('/public/admin/clip-one/assets/products/attachment')}}/{{ $result->attachment }}" target="_blank" downlaod="{{ $result->attachment }}"><span>{{$result->attachment}}</span></a>
                                 <?php } ?> 
                              </div>
                           </div>
                        </div>

                     </div>
                     <div class="card-footer">
                        <div>
                           <button type="submit" class="btn btn-primary">Submit</button>
                           <a href="{{route('products.index')}}" class="btn btn-default btn-secondary">Back</a>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
            <div class="col-md-6"></div>
         </div>
      </div>
   </section>
</div>
@endsection

@section('script')
<script src="{{asset('assets/admin/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/jquery-validation/additional-methods.min.js')}}"></script>

<script>
   var old_status = $('#status').val();
   if (old_status == 'Coming Soon') {
      $('#upcoming_quantity_div').hide();
   }else{
      $('#upcoming_quantity_div').show();
   }

   $('#status').on('change',function(){
      var status = $('#status').val();

      if (status == 'Coming Soon') {
         $('#upcoming_quantity_div').hide();
      }else{
         $('#upcoming_quantity_div').show();
      }
   });
</script>

<script>
$('.select12').select2({
   theme: 'bootstrap4',
   minimumResultsForSearch: Infinity
});

$('#year').inputmask('yyyy', { 'placeholder': 'yyyy' });

$('#description').summernote({
   height: 150,
   toolbar: [
    ['style', ['style']],
    ['font', ['bold', 'italic', 'underline', 'clear']],
    ['fontname', ['fontname']],
    ['color', ['color']],
    ['para', ['ul', 'ol', 'paragraph']],
    ['height', ['height']],
    ['table', ['table']],
    ['insert', ['link', 'picture', 'hr']],
    ['view', ['fullscreen', 'codeview']],
    ['help', ['help']]
   ],
});

$(function () {
   $('#quickForm').validate({
      rules: {
         stock_number: {
            required: true
         },
         backorder_number: {
            required: true
         },
         title: {
            required: true
         },
         date: {
            required: true
         },
         make: {
            required: true
         },
         model: {
            required: true
         },
         year: {
            required: true
         },
         hours: {
            required: true
         },
         weight: {
            required: true
         },
         price: {
            required: true
         },
         type: {
            required: true
         },
         status: {
            required: true
         },
      },
      messages: {
         stock_number: {
            required: "Please enter stock number",
         },
         backorder_number: {
            required: "Please enter backorder number",
         },
         title: {
            required: "Please enter title",
         },
         date: {
            required: "Please select date",
         },
         make: {
            required: "Please enter make",
         },
         model: {
            required: "Please enter model",
         },
         year: {
            required: "Please enter year",
         },
         hours: {
            required: "Please enter hours",
         },
         weight: {
            required: "Please enter weight",
         },
         price: {
            required: "Please enter price",
         },
         type: {
            required: "Please select type",
         },
         status: {
            required: "Please select status",
         },
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
         error.addClass('invalid-feedback');
         element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
         $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
         $(element).removeClass('is-invalid');
      }
   });
});

</script>

<script>
   $(document).ready(function(){
     $('.product-edit-btn').on('click',function(){
         var id = $(this).data('id');
         
         $.ajax({
             url: "{{ url('admin/products/image/delete') }}/"+id,
             method: "get",
             success: function (response) {
                if(response.msg == 'success'){
                    alert('Image deleted successfully!');
                    location.reload();
                }
             }
         });
     });
   });
</script>

@endsection
  


