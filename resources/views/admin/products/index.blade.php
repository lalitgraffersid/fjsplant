@extends('admin.layout.master')
@section('content')

<?php 
   $current_route = \Request::route()->getName();
   $routeArr = explode('.', $current_route);
   $section = $routeArr[0];
   $action = $routeArr[1];

   $data = App\Helpers\AdminHelper::checkAddButtonPermission($section,Auth::user()->id);
   // $user_id = Request::get('user_id');
   // $status = Request::get('status');
?>

<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Products</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Product List</li>
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
                           <a href="{{route('products.add')}}" class="btn btn-info float-right"><i class="fas fa-plus"></i> {{$data['actionData']->action_title}} </a>
                        </div>
                     @endif
                     <div class="card-body">
                        <form action="{{route('products.index')}}" method="GET">
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="row">
                                    <div class="col-3">
                                       <div class="form-group">
                                          <select class="select12 form-control" name="category_id" style="width: 100%;" data-placeholder="Select Category">
                                             <option value="">Select Category</option>
                                             @foreach($categories as $value)
                                                <option value="{{$value->id}}" <?php if(!empty($category_id) && $category_id == $value->id){ echo "selected"; } ?> >{{$value->name}}</option>
                                             @endforeach
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-3">
                                       <div class="form-group">
                                          <select class="select12 form-control" name="dealer_id" style="width: 100%;" data-placeholder="Select Dealer">
                                             <option value="">Select Dealer</option>
                                             @foreach($dealers as $value)
                                                <option value="{{$value->id}}" <?php if(!empty($dealer_id) && $dealer_id == $value->id){ echo "selected"; } ?> >{{$value->name}}</option>
                                             @endforeach
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-2">
                                       <div class="form-group">
                                          <select class="select12 form-control" name="status" style="width: 100%;" data-placeholder="Select Status">
                                             <option value="">Select Status</option>
                                             <option value="Coming Soon" <?php if(!empty($status) && $status == 'Coming Soon'){ echo "selected"; } ?> >Coming Soon</option>
                                             <option value="In Stock" <?php if(!empty($status) && $status == 'In Stock'){ echo "selected"; } ?> >In Stock</option>
                                             <option value="Sold" <?php if(!empty($status) && $status == 'Sold'){ echo "selected"; } ?> >Sold</option>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-2">
                                       <div class="form-group">
                                          <input type="text" name="title" class="form-control" placeholder="Search Title" value="<?php if(!empty($title)){echo $title; } ?>">
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
                        <table class="table table-bordered" >
                           <thead>
                              <tr>
                                 <th>S.No.</th>
                                 <th>Type</th>
                                 <th>Title</th>
                                 <th>Serial Number</th>
                                 <th>Category</th>
                                 <th>Dealer</th>
                                 <th>Image</th>
                                 <th>Status</th>
                                 <!-- <th>Quantity Available</th> -->
                                 <th style="width: 200px">Action</th>
                              </tr>
                           </thead>
                           
                           @if(count($results)>0)
                           <tbody class="tablecontents" id="tablecontents">
                              <?php $i = 1; ?>
                              @foreach($results as $key => $result)
                              <?php 
                                 $count = DB::table('products')->where('title',$result->title)->count();
                                 $category = DB::table('categories')->where('id',$result->category_id)->first();
                                 $dealer = DB::table('dealers')->where('id',$result->dealer_id)->first();
                                 $image = DB::table('product_images')->where('product_id',$result->id)->first();
                              ?>
                              <tr class="row1" id="row1" data-id="{{ $result->id }}">
                                 <td>{{$result->order_no}}</td>
                                 <td>{{$result->type}}</td>
                                 <td>{{$result->title}}</td>
                                 <td>{{$result->stock_number}}</td>
                                 <td>{{$category->name}}</td>
                                 <td>{{$dealer->name}}</td>
                                 <td>
                                    @if(!empty($image->image))
                                       <ul class="list-inline"><li class="list-inline-item"><img alt="Avatar" class="table-avatar" src="{{url('/public/admin/clip-one/assets/products/thumbnail')}}/{{$image->image}}"></li></ul>
                                    @else
                                       <ul class="list-inline"><li class="list-inline-item"><img alt="Avatar" class="table-avatar" src="{{url('/assets/no_image.jpg')}}" width="100px"></li></ul>
                                    @endif
                                 </td>
                                 <td>{{$result->status}}</td>
                                 <!-- <td style="text-align: center;">{{$count}}</td> -->
                                 <td>
                                    @foreach ($action_ids as $key1 => $action_id) 
                                       <?php $action = DB::table('actions')->where('id',$action_id)->first(); ?>
                                       @if ($action->action_slug == 'edit' || $action->action_slug == 'delete' || $action->action_slug == 'view')
                                          <a href="{{route('products.'.$action->action_slug,$result->id)}}" class="btn btn-{{$action->class}} btn-sm" data-placement="top" data-original-title="{{$action->action_title}}" style="width: 83px;" ><i class="{{$action->icon}}"></i>{{$action->action_title}}</a>&nbsp;
                                       @endif
                                    @endforeach
                                       <button class="btn btn-block btn-secondary btn-sm mt-2 add_more" data-placement="top" data-original-title="Add More" style="display: block;" data-toggle="modal" data-target="#modal-default" data-id="{{$result->id}}"><i class="fas fa-plus"></i>Add More</button>&nbsp;
                                 </td>
                              </tr>
                              <?php $i++; ?>
                              
                                 @section('script')
                                 @if (empty($category_id) && empty($dealer_id) && empty($status) && empty($title))                          
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
                                                url: "{{url('admin/products/sortProducts')}}",
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
                                 @endif
                                 
                                 @endsection
                              @endforeach
                           </tbody>
                           @endif
                        </table>

                        <!-- Add more modal -->
                        <div class="modal fade" id="modal-default">
                           <div class="modal-dialog">
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <h4 class="modal-title">Add More Machines</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                    </button>
                                 </div>
                                 <form id="quickForm" action="{{route('products.add_more')}}" method="POST" enctype="multipart/form-data" >
                                    {{csrf_field()}}
                                    <input type="hidden" name="id" id="product_id" value="">

                                    <div class="modal-body">

                                       <div id="addDivA">
                                          <div class="row">
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                   <label for="stock_quantity">Stock Quantity</label>
                                                   <input type="number" name="stock_quantity" class="form-control" id="stock_quantityA" min="1" value="1" placeholder="Stock Quantity" >
                                                </div>
                                             </div>

                                             <div class="col-md-6">
                                                <div class="form-group">
                                                   <label for="stock_number">Serial Number</label>
                                                   <input type="text" name="stock_number[]" class="form-control stock_number" id="stock_number"  placeholder="Serial Number" >
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                       <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                 </form>
                              </div>
                           </div>
                        </div>
                        <!-- Add more modal -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
   </div>
</div>
@endsection
