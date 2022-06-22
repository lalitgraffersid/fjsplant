@extends('admin.layout.master')
@section('content')

<?php 
   $current_route = \Request::route()->getName();
   $routeArr = explode('.', $current_route);
   $section = $routeArr[0];
   $action = $routeArr[1];

   $data = App\Helpers\AdminHelper::checkAddButtonPermission($section,Auth::user()->id);
   $customers = DB::table('customers')->where('status','1')->get();
   $machines = DB::table('products')->get();
   $from = Request::get('from');
   $to = Request::get('to');
   $customer = Request::get('customer');
   $machine = Request::get('machine');
?>
 
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Quotes</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Quotes List</li>
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
                     <form action="{{route('quotes.index')}}" method="GET">
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
                           <div class="col-md-3">
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
                           <div class="col-md-3">
                              <div class="form-group">
                                 <label>Machine:</label>
                                 <select name="machine" class="select12 form-control">
                                    <option value="">Select Machine</option>
                                    @foreach($machines as $value)
                                       <option value="{{$value->id}}" <?php if($machine == $value->id){echo "selected";} ?> >{{$value->title}}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-2">
                              <div class="form-group">
                                 <label></label>
                                 <button type="submit" class="btn btn-info" style="width: 97%;margin-top: 30px;">Search</button>
                              </div>
                           </div>
                        </div>
                     </form>

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
      theme: 'bootstrap4',
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
  


