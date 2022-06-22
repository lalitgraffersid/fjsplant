@extends('admin.layout.master')
@section('content')

<style>
   .dataTables_filter, .dataTables_info { display: none; }
</style>

<?php 
   $current_route = \Request::route()->getName();
   $routeArr = explode('.', $current_route);
   $section = $routeArr[0];
   $action = $routeArr[1];

   $data = App\Helpers\AdminHelper::checkAddButtonPermission($section,Auth::user()->id);

   $type = Request::get('type');
   $status = Request::get('status');
?>
 
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Stocks</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Stock List</li>
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
           
           <div class="col-lg-12"> 
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

              <!-- /.card-header -->
              <div class="card-body">
                <div class="col-md-12">
                 <form action="{{route('stock_report.index')}}" method="GET">
                     {{csrf_field()}}
                      <div class="row">
                          <div class="col-md-12">
                              <div class="row">
                                  <div class="col-3">
                                      <div class="form-group">
                                          <select class="select12" name="type" style="width: 100%;" data-placeholder="Select Type">
                                              <option value="">Select Type</option>
                                              <option value="New" <?php if($type == 'New'){echo "selected";} ?>>New</option>
                                              <option value="Used" <?php if($type == 'Used'){echo "selected";} ?>>Used</option>
                                          </select>
                                      </div>
                                  </div>
                                  <div class="col-3">
                                      <div class="form-group">
                                          <select class="select12" name="status" style="width: 100%;" data-placeholder="Select Status">
                                              <option value="">Select Status</option>
                                              <option value="Coming Soon" <?php if($status == 'Coming Soon'){echo "selected";} ?>>Coming Soon</option>
                                              <option value="In Stock" <?php if($status == 'In Stock'){echo "selected";} ?>>In Stock</option>
                                              <option value="Sold" <?php if($status == 'Sold'){echo "selected";} ?>>Sold</option>
                                          </select>
                                      </div>
                                  </div>
                                  <div class="col-2">
                                      <div class="form-group">
                                          <label></label>
                                          <button type="submit" class="btn btn-info" style="width: 97%;">Search</button>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </form>
              </div>

                {!! $dataTable->table(['class'=>'table dataTable no-footer']) !!}
              </div>
              <!-- /.card-body -->
            </div>
          </div>
         </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection
@section('script')

{!! $dataTable->scripts() !!}

<script>
   $('.select12').select2({
   theme: 'bootstrap4'
});
</script>

@endsection
  


