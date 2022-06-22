@extends('admin.layout.master')
@section('content')

<style>
   ul.check-list li{
      display: inline-block;
      width: 100px;
   }
</style>

<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Dealer</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                  <li class="breadcrumb-item active">Dealer Edit</li>
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
                     <h3 class="card-title">Edit <small>Dealer</small></h3>
                  </div>
                  @if (count($errors) > 0)       
                     <div class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        @foreach ($errors->all() as $error)
                           <span>{{ $error }}</span><br/>
                        @endforeach    
                     </div>         
                  @endif
                  <form id="quickForm" action="{{route('dealers.update')}}" method="POST" enctype="multipart/form-data">
                     {{csrf_field()}}
                     <input type="hidden" name="id" value="{{$result->id}}">

                     <div class="card-body">
                        <div class="form-group">
                           <label for="name">Dealer Name</label>
                           <input type="text" name="name" value="{{$result->name}}" class="form-control" id="name" placeholder="Dealer Name">
                        </div>

                        <div class="form-group">
                           <label for="image">Dealer Image</label>
                           <input type="file" name="image" class="form-control" id="image" placeholder="Image"><br>

                           @if (!empty($result->image))
                              <img src="{{ asset('/public/admin/clip-one/assets/dealers/thumbnail') }}/{{$result->image}}" alt="" class="product-edit-img">
                           @endif

                        </div>

                        <ul class="check-list">
                           <li>
                              <input class="custom-control-input radio_btn" type="radio" id="radio1" name="type" value="1" {{$result->type == '1' ? 'checked' : ''}}>
                              <label for="radio1" class="custom-control-label">Video URL</label>
                           </li>
                           <li>
                              <input class="custom-control-input radio_btn" type="radio" id="radio2" name="type" value="2" {{$result->type == '2' ? 'checked' : ''}}>
                              <label for="radio2" class="custom-control-label">Upload File</label>
                           </li>
                        </ul><br>

                        <div id="video_url">
                           <div class="form-group">
                              <label for="video_url">Video URL</label>
                              <input type="text" name="video_url" value="{{$result->video_url}}" class="form-control" id="video_url" placeholder="Video URL">
                           </div>
                           @if(!empty($result->video_url))
                           <div>
                              <iframe src="{{$result->video_url}}" height="100%" width="300" style="border:1px solid black;"></iframe>
                           </div>
                           @endif
                        </div>

                        <div id="video_file">
                           <div class="form-group">
                              <label for="video_file">Video File</label>
                              <input type="file" name="video_file" class="form-control" id="video_file">
                           </div>
                           @if(!empty($result->video_file))
                           <div>
                              <video loop="true" autoplay="autoplay" muted playsinline class="inner-video" width="300px">
                                 <source src="{{url('/public/admin/clip-one/assets/dealers/video_file/')}}/{{$result->video_file}}" type="video/mp4" >
                              </video>
                           </div>
                           @endif

                        </div>

                     </div>
                     <div class="card-footer">
                        <div>
                           <button type="submit" class="btn btn-primary">Submit</button>
                           <a href="{{route('dealers.index')}}" class="btn btn-default btn-secondary">Back</a>
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
         name: {
            required: true
         }
      },
      messages: {
         name: {
            required: "Please enter a Dealer Name",
         }
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
      var initial_val = $('input[name="type"]:checked').val();
      if (initial_val == '1') {
         $('#video_url').show();
         $('#video_file').hide();
      }else{
         $('#video_file').show();
         $('#video_url').hide();
      }

      $('.radio_btn').on('change',function(){
         var val = $(this).val();

         if (val == '1') {
            $('#video_url').show();
            $('#video_file').hide();
         }else{
            $('#video_file').show();
            $('#video_url').hide();
         }
      });
   });

</script>

@endsection
  


