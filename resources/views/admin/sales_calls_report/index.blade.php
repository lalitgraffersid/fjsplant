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

   $status = Request::get('status');
   $from = Request::get('from');
   $to = Request::get('to');
   $customer = Request::get('customer');
   $user_id = Request::get('user_id');
?>
 
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Sales Calls</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                  <li class="breadcrumb-item active">Sales Calls List</li>
               </ol>
            </div>
         </div>
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
                        <form action="{{route('sales_calls_report.index')}}" method="GET">
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
                                          <label>Status:</label>
                                          <select class="select12" name="status" style="width: 100%;" data-placeholder="Select Status">
                                             <option value="">Select Status</option>
                                             <option value="New" <?php if($status == 'New'){echo "selected";} ?>>New</option>
                                             <option value="In Progress" <?php if($status == 'In Progress'){echo "selected";} ?>>In Progress</option>
                                             <option value="On Hold" <?php if($status == 'On Hold'){echo "selected";} ?>>On Hold</option>
                                             <option value="Lost" <?php if($status == 'Lost'){echo "selected";} ?>>Lost</option>
                                             <option value="Closed" <?php if($status == 'Closed'){echo "selected";} ?>>Closed</option>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-2">
                                       <div class="form-group">
                                          <label></label>
                                          <button type="submit" class="btn btn-info" style="width: 97%;margin-top: 30px;">Search</button>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </form>
                     </div>
                     {!! $dataTable->table(['class'=>'table dataTable no-footer projects']) !!}
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
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
  


