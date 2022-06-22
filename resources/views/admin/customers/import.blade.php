@extends('admin.layout.master')
@section('content')

<style>
.user-block img {
   float: left;
   height: 80px;
   width: 80px;
   object-fit: cover;
   margin: 0 10px 0 0;
}

.user-block .username {
   margin: 20px 0 0 0;
}
ul.cus-info{
   list-style-type: none;
   padding: 6px;
   height: auto;
}
ul.cus-info li{
   font-size: 16px;
   margin: 0 0 10px 0;
   list-style-type: none;
   padding: 0 0 0 0;
}
ul.cus-info li span{
   display: inline-block;
   width: 130px;
   vertical-align: top;
}
ul.cus-info li span.cus-message{
   width: 80%;
   height: auto;
}
</style>

<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Import Excel</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active">Import Excel</li>
               </ol>
            </div>
         </div>
      </div>
   </section>

   <section class="content">
      <div class="card">
         <!-- <div class="card-header">
            <h3 class="card-title">Projects Detail</h3>
         </div> -->
         <div class="card-body">
            <div class="row">
               <div class="col-12 col-md-12 col-lg-12">
                  <div class="row">
                     <div class="col-12 col-sm-12">
                        <h5>Please click download to get the CSV format sample&nbsp; <a href="{{asset('assets/file_format/customers/csv_file_format.csv')}}" class="btn btn-primary" download="csv_file_format.csv">Download</a></h5>
                     </div>
                  </div>
                  
                  <hr>

                  <form id="quickForm" action="{{route('customers.import')}}" method="POST" enctype="multipart/form-data">
                     {{csrf_field()}}
                     <div class="row">
                        <div class="col-12">
                           <h4></h4>
                           <div class="post">
                              <div class="form-group">
                                 <label for="file">Upload CSV</label>
                                 <input type="file" name="csv" id="file" class="form-control">
                              </div>
                           </div>
                        </div>
                     </div>

                     <div class="card-footer">
                        <div>
                           <a href="{{route('customers.index')}}" class="btn btn-primary btn-secondary float-sm-right">Back</a>
                           <button type="submit" class="btn btn-info float-sm-right" style="margin-right: 5px">Submit</button>
                        </div>
                     </div>
                  </form>

               </div>
            </div>
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
         csv: {
            required: true
         },
      },
      messages: {
         csv: {
            required: "",
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