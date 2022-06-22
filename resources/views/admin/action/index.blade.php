@extends('admin.layout.master')
@section('content')
 
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Actions</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Actions List</li>
               </ol>
            </div><!-- /.col -->
         </div><!-- /.row -->
      </div><!-- /.container-fluid -->
   </div>
    <!-- /.content-header -->

   <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- @if (count($errors) > 0)
               <div class="alert alert-danger val-error-list">
                  <ul>
                     @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                     @endforeach
                  </ul>
               </div>
            @endif
            @if(Session::has('message'))
               <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{Session::get('message')}}</p>
            @endif -->
            <div class="card">
               <div class="card-header float-right">
                  <a href="{{route('actions.add')}}" class="btn btn-info float-right"><i class="fas fa-plus"></i> Add Action</a>
               </div>
              <!-- /.card-header -->
              <div class="card-body">
                {!! $dataTable->table() !!} 
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
@endsection

@section('script')

{!! $dataTable->scripts() !!}

@endsection
  


