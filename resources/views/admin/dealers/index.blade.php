@extends('admin.layout.master')
@section('content')

<?php 
   $current_route = \Request::route()->getName();
   $routeArr = explode('.', $current_route);
   $section = $routeArr[0];
   $action = $routeArr[1];

   $data = App\Helpers\AdminHelper::checkAddButtonPermission($section,Auth::user()->id);
?>
 
<div class="content-wrapper">    
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Dealers/Brands</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Dealers/Brands List</li>
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
                        <a href="{{route('dealers.add')}}" class="btn btn-info float-right"><i class="fas fa-plus"></i> {{$data['actionData']->action_title}} </a>
                     </div>
                  @endif
                  <div class="card-body">
                     <table class="table table-bordered" >
                        <thead>
                           <tr>
                              <th>S.No.</th>
                              <th>Name</th>
                              <th>Image</th>
                              <th>Status</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        
                        @if(count($results)>0)
                        <tbody class="tablecontents" id="tablecontents">
                           <?php $i = 1; ?>
                           @foreach($results as $key => $result)
                           <tr class="row1" id="row1" data-id="{{ $result->id }}">
                              <td>{{$result->order_no}}</td>
                              <td>{{$result->name}} {{Auth::user()->user_type}}</td>
                              <td>
                                 <ul class="list-inline"><li class="list-inline-item"><img alt="Avatar" class="table-avatar" src="{{url('/admin/clip-one/assets/dealers/thumbnail')}}/{{$result->image}}"></li></ul>
                                 <!-- <ul class="list-inline"><li class="list-inline-item"><img alt="Avatar" class="table-avatar" src="{{asset('/admin/clip-one/assets/dealers/thumbnail')}}/{{$result->image}}"></li></ul> -->
                              </td>
                              <td>
                                 @if (!empty($checkStatusAction) && (!empty($checkStatusPermission) || Auth::user()->user_type == 'admin'))
                                    @if($result->status == '1')
                                       <a title="Status" href="{{route('dealers.status',[$result->id]) }}"  onclick="return confirm('Are you sure want to change status?')"> <span class="btn btn-success btn-sm">Active</span></a> 
                                    @endif
                                    @if($result->status == '0')
                                       <a title="Status" href="{{ route('dealers.status',[$result->id]) }}"  onclick="return confirm('Are you sure want to change status?')"><span class="btn btn-danger btn-sm">Inactive</span> </a> 
                                    @endif
                                 @else
                                    @if($result->status == '1')
                                       <a href="javascript:void(0)" class="btn btn-success btn-sm">Active</a>
                                    @endif
                                    @if($result->status == '0')
                                       <a href="javascript:void(0)" class="btn btn-danger btn-sm">Inactive</a>
                                    @endif
                                 @endif
                              </td>
                              <td>
                                 @foreach ($action_ids as $key1 => $action_id) 
                                    <?php $action = DB::table('actions')->where('id',$action_id)->first(); ?>
                                    @if ($action->action_slug == 'edit' || $action->action_slug == 'delete' || $action->action_slug == 'view')
                                       <a href="{{route('dealers.'.$action->action_slug,$result->id)}}" class="btn btn-{{$action->class}} btn-sm" data-placement="top" data-original-title="{{$action->action_title}}"><i class="{{$action->icon}}"></i>{{$action->action_title}}</a>&nbsp;
                                    @endif
                                 @endforeach
                              </td>
                           </tr>
                           <?php $i++; ?>
                              @section('script')                              
                              <script type="text/javascript">
                                 $(function () {
                                    $( "#tablecontents" ).sortable({
                                       items: "tr",
                                       cursor: 'move',
                                       opacity: 0.9,
                                       update: function() {
                                          sendOrderToServer();
                                       }
                                    });

                                    function sendOrderToServer() {  
                                       var order = [];
                                       $('tr.row1').each(function(index,element) {
                                          order.push({
                                             id: $(this).attr('data-id'),
                                             position: index+1
                                          });
                                       });

                                       $.ajax({
                                          type: "POST", 
                                          dataType: "json", 
                                          url: "{{url('admin/dealers/sortBrands')}}",
                                          data: {
                                             order:order,
                                             _token: '{{csrf_token()}}'
                                          },
                                          success: function(response) {
                                             if (response.status == "success") {
                                                toastr.success("Sorted successfuly.",'Success');
                                                location.reload();
                                             } else {
                                                toastr.error("Try Again!",'Error');
                                                location.reload();
                                             }
                                          }
                                       });
                                    }
                                 });
                              </script>
                              @endsection
                           @endforeach
                        </tbody>
                        @endif
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
@endsection
@section('script')


@endsection
  


