@extends('admin.layout.master')
@section('content')
 
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Plan</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                  <li class="breadcrumb-item active">Plan Add</li>
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
                     <h3 class="card-title">Add <small>Plan</small></h3>
                  </div>
                  @if (count($errors) > 0)       
                     <div class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        @foreach ($errors->all() as $error)
                           <span>{{ $error }}</span><br/>
                        @endforeach    
                     </div>         
                  @endif
                  <form id="quickForm" action="{{route('plans.save')}}" method="POST" enctype="multipart/form-data">
                     {{csrf_field()}}
                     <div class="card-body">
                        
                        <div class="form-group">
                           <label for="title">Type</label>
                           <select name="type" id="type" class="form-control">
                              <option value="">Select Type</option>
                              <option value="Individual">Individual</option>
                              <option value="Family">Family</option>
                           </select>
                        </div>

                        <div class="form-group">
                           <label for="title">Title</label>
                           <input type="text" name="title" class="form-control" id="title" placeholder="Title">
                        </div>

                        <div class="form-group">
                           <label for="price">Price</label>
                           <input type="number" name="price" min="0" class="form-control" id="price" placeholder="Price">
                        </div>

                        <div class="form-group">
                           <label for="duration">Duration</label>
                           <select name="duration" id="duration" class="form-control">
                              <option value="">Select Duration</option>
                              <option value="Month">Month</option>
                              <option value="Year">Year</option>
                           </select>
                        </div>

                        <div class="form-group">
                           <label for="no_of_user">No Of User</label>
                           <input type="number" name="no_of_user" min="0" class="form-control" id="no_of_user" placeholder="No Of User">
                        </div>

                     </div>
                     <div class="card-footer">
                        <div>
                           <button type="submit" class="btn btn-primary">Submit</button>
                           <a href="{{route('plans.index')}}" class="btn btn-default btn-secondary">Back</a>
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
$(function () {
   $('#quickForm').validate({
      rules: {
         type: {
            required: true
         },
         title: {
            required: true
         },
         price: {
            required: true
         },
         duration: {
            required: true
         },
         no_of_user: {
            required: true
         },
      },
      messages: {
         type: {
            required: "Please select type",
         },
         title: {
            required: "Please enter a title",
         },
         price: {
            required: "Please enter price",
         },
         duration: {
            required: "Please enter duration",
         },
         no_of_user: {
            required: "Please enter no of user allowed",
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

@endsection
  


