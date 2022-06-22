@extends('admin.layout.master')
@section('content')
 
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Role</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Role Add</li>
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
                     <h3 class="card-title">Add <small>Role</small></h3>
                  </div>
                  @if (count($errors) > 0)       
                     <div class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        @foreach ($errors->all() as $error)
                           <span>{{ $error }}</span><br/>
                        @endforeach    
                     </div>         
                  @endif
                  <form id="quickForm" action="{{route('roles.update')}}" method="POST">
                     {{csrf_field()}}

                     <input type="hidden" name="id" value="{{ $role->id }}">

                     <div class="card-body">
                        <div class="form-group">
                           <label for="section_id">Section</label>
                           <select name="section_id" class="section_id select12 form-control" id="section_id" data-placeholder="Select a Section" style="width: 100%;" >
                              <option value="">Select Section</option>
                              @foreach($sections as $section)
                                 <option value="{{$section->id}}" <?php if($section->id == $role->section_id){echo "selected";} ?>>{{$section->section_title}}</option>
                              @endforeach
                           </select>
                        </div>
                        <div class="form-group">
                           <label for="action_id">Actions</label>
                           <select name="action_id[]" class="action_id select12 form-control" id="action_id" data-placeholder="Select a Section" style="width: 100%;" multiple="multiple">
                              <option value="">Select Actions</option>
                              @foreach($actions as $action)
                                 <option value="{{$action->id}}" <?php if(in_array($action->id,$roleActions)){echo "selected";} ?> >{{$action->action_title}}</option>
                              @endforeach
                           </select>
                        </div>
                        <div class="form-group">
                           <label for="action_title">Role Name</label>
                           <input type="text" name="name" value="{{ $role->name }}" class="form-control" id="action_title" placeholder="Role name">
                        </div>
                     </div>
                     <div class="card-footer">
                        <div>
                           <button type="submit" class="btn btn-primary">Submit</button>
                           <a href="{{route('roles.index')}}" class="btn btn-default btn-secondary">Back</a>
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
   // $.validator.setDefaults({
   //    submitHandler: function () {
   //       alert( "Form successful submitted!" );
   //    }
   // });
   $('#quickForm').validate({
      rules: {
         section_id: {
            required: true
         },
         "action_id[]": {
            required: true
         },
         name: {
            required: true
         },
      },
      messages: {
         section_id: {
            required: "Please select a section",
         },
         "action_id[]": {
            required: "Please select actions",
         },
         name: {
            required: "Please enter a name",
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

$('.select12').select2({
   theme: 'bootstrap4'
});

</script>

@endsection
  


