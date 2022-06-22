@extends('admin.layout.master')
@section('content')

<style>
   .cus-check{
    width: 26px;
    height: 26px;
    margin: 0 auto;
    display: block;
   }
</style>

<?php 
   $current_route = \Request::route()->getName();
   $routeArr = explode('.', $current_route);
   $section = $routeArr[0];
   $action = $routeArr[1];

   $data = App\Helpers\AdminHelper::checkAddButtonPermission($section,Auth::user()->id);
   $account_type = Auth::user()->account_type;
   $from = Request::get('from');
   $to = Request::get('to');
   $customer = Request::get('customer');
?>

<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Sales Orders</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Sales Orders List</li>
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
                  <div class="card-body">
                     <form action="{{route('sales_order.index')}}" method="GET">
                        <div class="row">
                           <div class="col-md-3">
                              <div class="form-group">
                                 <label>From:</label>
                                 <input type="text" name="from" class="form-control datepicker" value="<?php if(!empty($from)){echo $from; } ?>"  autocomplete="off">
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="form-group">
                                 <label>To:</label>
                                 <input type="text" name="to" class="form-control datepicker" value="<?php if(!empty($to)){echo $to; } ?>" autocomplete="off">
                              </div>
                           </div>
                           <div class="col-md-4">
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
                                 <label></label>
                                 <button type="submit" class="btn btn-info" style="width: 97%;margin-top: 30px;">Search</button>
                              </div>
                           </div>
                        </div>
                     </form>
                     <table class="table table-bordered" >
                        <thead>
                           <tr style="background-color: #ccc;">
                              <th>Order Date</th>
                              <th>Sales Rep</th>
                              <th>Customer</th>
                              <th>Depot</th>
                              <th>Order #</th>
                              <th>Hitch</th>
                              <th>Buckets</th>
                              <th>Extra</th>
                              <th>Delivery Date</th>
                              <th style="width: 50px;text-align: center;">PAID</th>
                              <th style="width: 50px;text-align: center;">PDI</th>
                              <th style="width: 50px;text-align: center;">Delivered</th>
                              <th style="width: 50px;text-align: center;">Action</th>
                           </tr>
                        </thead>
                        
                        @if(count($results)>0)
                        <tbody class="tablecontents" id="tablecontents">
                           <?php $i = 1; ?>
                           @foreach($results as $key => $result)
                           <?php 
                              $quote = DB::table('quotes')->where('id',$result->quote_id)->first();
                              $lead = DB::table('leads')->where('id',$quote->lead_id)->first();
                              $rep = DB::table('users')->where('id',$lead->user_id)->first();
                              $customer = DB::table('customers')->where('id',$result->customer_id)->first();
                           ?>
                           <tr class="row1" id="row1" data-id="{{ $result->id }}">
                              <td>{{$result->date}}</td>
                              <td>{{$rep->name}}</td>
                              <td>{{$customer->name}}</td>
                              <td>{{$result->depot}}</td>
                              <td>{{$result->id}}</td>
                              <td>{{$result->hitch}}</td>
                              <td>{{$result->buckets}}</td>
                              <td>{{$result->extra}}</td>
                              <td>
                                 <?php 
                                 if($result->delivery_date != '0000-00-00' && $result->delivery_date != ''){
                                    echo $result->delivery_date;
                                 }else{
                                    echo "N/A";
                                 }
                                 ?>
                              </td>
                              <td>
                                 <div class="icheck-success d-inline">
                                    <input type="checkbox" name="payment_confirm" id="payment_confirm_{{$key}}" class="payment_confirm" value="1" data-id="{{$result->id}}" <?php if($result->payment_confirm == '1'){echo "checked";} ?>>
                                    <label for="payment_confirm_{{$key}}"></label>
                                 </div>
                              </td>
                              <td>
                                 <div class="icheck-success d-inline">
                                    <input type="checkbox" name="PDI_status" id="PDI_status_{{$key}}" class="PDI_status" value="1" data-id="{{$result->id}}" <?php if($result->PDI_status == '1'){echo "checked";} ?>>
                                    <label for="PDI_status_{{$key}}"></label>
                                 </div>
                              </td>
                              <td>
                                 <div class="icheck-success d-inline">
                                    <input type="checkbox" name="delivered" id="delivered_{{$key}}" class="delivered" value="1" data-id="{{$result->id}}" <?php if($result->delivered == '1'){echo "checked";} ?>>
                                    <label for="delivered_{{$key}}"></label>
                                 </div>
                              </td>
                              <td>
                                 @foreach ($action_ids as $key1 => $action_id) 
                                    <?php $action = DB::table('actions')->where('id',$action_id)->first(); ?>
                                    @if ($action->action_slug == 'edit' || $action->action_slug == 'delete' || $action->action_slug == 'view')
                                       <a href="{{route('sales_order.'.$action->action_slug,$result->id)}}" class="btn btn-{{$action->class}} btn-sm" data-placement="top" data-original-title="{{$action->action_title}}" style="width: 83px;" ><i class="{{$action->icon}}"></i>{{$action->action_title}}</a>&nbsp;
                                    @endif
                                 @endforeach
                              </td>
                           </tr>
                           <?php $i++; ?>
                           @endforeach
                        </tbody>
                        @endif
                     </table>

                     <div class="row">
                        <div class="col-lg-12 float-right mt-4">
                           <div class="float-right">
                              {{ $results->links() }}
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
@endsection

@section('script')
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

<script>
   $(".payment_confirm").click(function(){
      if($(this).is(":checked")) {
         var payment_confirm = $(this).val();  
      }else{
         var payment_confirm = '0';
      }
      var id = $(this).data('id');
      var type = 'payment_confirm';

      $.ajax({
         url: "{{ url('admin/sales_order/update') }}",
         method: "POST",
         data: {_token: '{{ csrf_token() }}', payment_confirm: payment_confirm, id: id,type: type},
         success: function (response) {
            if(response.status == 'success'){
               toastr.success('Updated successfully.', 'Success');
               setTimeout(function(){ 
                  location.reload();
               }, 2000);
            }else{
               toastr.error('Something went wrong! Try Again', 'Error');
               return false;
            }
         }
      });
   });
   </script>

   <script>
   $('.PDI_status').click(function(){
      if($(this).is(":checked")) {
         var PDI_status = $(this).val();  
      }else{
         var PDI_status = '0';
      }
      var id = $(this).data('id');
      var type = 'PDI_status';

      $.ajax({
         url: "{{ url('admin/sales_order/update') }}",
         method: "POST",
         data: {_token: '{{ csrf_token() }}', PDI_status: PDI_status, id: id,type: type},
         success: function (response) {
            if(response.status == 'success'){
               toastr.success('Updated successfully.', 'Success');
               setTimeout(function(){ 
                  location.reload();
               }, 2000);
            }else{
               toastr.error('Something went wrong! Try Again', 'Error');
               return false;
            }
         }
      });
   });
   </script>

   <script>
   $('.delivered').click(function(){
      if($(this).is(":checked")) {
         var delivered = $(this).val();  
      }else{
         var delivered = '0';
      }
      var id = $(this).data('id');
      var type = 'delivered';

      $.ajax({
         url: "{{ url('admin/sales_order/update') }}",
         method: "POST",
         data: {_token: '{{ csrf_token() }}', delivered: delivered, id: id,type: type},
         success: function (response) {
            if(response.status == 'success'){
               toastr.success('Updated successfully.', 'Success');
               setTimeout(function(){ 
                  location.reload();
               }, 2000);
            }else{
               toastr.error('Something went wrong! Try Again', 'Error');
               return false;
            }
         }
      });
   });
   </script>

@endsection