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

   $customers = DB::table('customers')->where('status','1')->get();
   $users = DB::table('users')->where('user_type','user')->where('status','1')->get();

   $from = Request::get('from');
   $to = Request::get('to');
   $customer = Request::get('customer');
   $PDI_status = Request::get('PDI_status');
   $payment_confirm = Request::get('payment_confirm');
   $user_id = Request::get('user_id');
?>
 
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Sales Order</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">Sales Order List</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
           
           <div class="col-lg-12">
              <div class="card">
               @if(!empty($data['checkRole']) && (Auth::user()->user_type == 'admin' || !empty($data['checkPermission'])))
                  <div class="card-header float-right">
                     <a href="{{route('leads.add')}}" class="btn btn-info float-right"><i class="fas fa-plus"></i> Add</a>
                  </div>
               @endif

              <div class="card-body">
               <div class="col-md-12">
                 <form action="{{route('sales_order_report.index')}}" method="GET">
                     {{csrf_field()}}
                      <div class="row">
                          <div class="col-md-12">
                              <div class="row">
                                 <div class="col-md-2">
                                    <div class="form-group">
                                       <label>From:</label>
                                       <input type="text" name="from" class="form-control datepicker" value="<?php if(!empty($from)){echo $from; } ?>"  autocomplete="off">
                                    </div>
                                 </div>
                                 <div class="col-md-2">
                                    <div class="form-group">
                                       <label>To:</label>
                                       <input type="text" name="to" class="form-control datepicker" value="<?php if(!empty($to)){echo $to; } ?>" autocomplete="off">
                                    </div>
                                 </div>
                                 <div class="col-md-2">
                                    <div class="form-group">
                                       <label>Customer:</label>
                                       <select name="customer" class="select12 form-control">
                                          <option value="">Select Customer</option>
                                          @foreach($customers as $value)
                                             <option value="{{$value->id}}" <?php if($customer == $value->id){echo "selected";} ?> >{{$value->name}}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-md-2">
                                    <div class="form-group">
                                       <label>Sales Rep:</label>
                                       <select name="user_id" class="select12 form-control">
                                          <option value="">Select Sales Rep</option>
                                          @foreach($users as $value)
                                             <option value="{{$value->id}}" <?php if($user_id == $value->id){echo "selected";} ?> >{{$value->name}}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                                  <div class="col-2">
                                      <div class="form-group">
                                          <label>PDI Status:</label>
                                          <select class="select12" name="PDI_status" style="width: 100%;" data-placeholder="Select PDI Status">
                                              <option value="">Select PDI Status</option>
                                              <option value="1" <?php if($PDI_status == '1'){echo "selected";} ?>>Approved</option>
                                              <option value="0" <?php if($PDI_status == '0'){echo "selected";} ?>>Defected</option>
                                          </select>
                                      </div>
                                  </div>
                                  <div class="col-2">
                                      <div class="form-group">
                                          <label>PAID:</label>
                                          <select class="select12" name="payment_confirm" style="width: 100%;" data-placeholder="Select Payment Status">
                                              <option value="">Select Status</option>
                                              <option value="1" <?php if($payment_confirm == '1'){echo "selected";} ?>>Yes</option>
                                              <option value="0" <?php if($payment_confirm == '0'){echo "selected";} ?>>No</option>
                                          </select>
                                      </div>
                                  </div>
                              </div>
                          </div>
                           <div class="col-12">
                              <div class="form-group">
                                 <label></label>
                                 <button type="submit" class="btn btn-info" style="width: 100%;">Search</button>
                              </div>
                           </div>
                      </div>
                  </form>
               </div>
               <div class="table-responsive">
                  {!! $dataTable->table(['class'=>'table dataTable no-footer projects']) !!}
               </div>
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

<script>
   $( function() {
      $( ".datepicker" ).datepicker({
         dateFormat: "yy-mm-dd"
      });
   });
</script>

@endsection
  


