@extends('admin.layout.master')
@section('content')

<style>
  ul.cus-info li:last-child{
     margin: 20px 0 0 0;
    font-size: 30px;
    font-weight: 700;
}
</style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sales Order Details</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Sales Order Details</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
              <div class="row">

               <div class="col-12 col-sm-12">
                  <div class="machine-panel">
                     <h1>Selected Machines</h1>
                        <ul class="machine-list">
                           @foreach($products as $product)
                              <li><a href="#"><img src="{{url('/public/admin/clip-one/assets/products/original')}}/{{$product['image']}}" alt=""> <span>{{$product['title']}}</span><span>&#163;{{$product['price']}} X {{$product['quantity']}} = &#163;{{$product['total_price']}}</span></a></li>
                           @endforeach
                        </ul>
                  </div>
               </div>

              </div>
               <div class="row">
                  <div class="col-md-12">
                     <h4></h4>
                     <div class="post">
                        <!-- /.user-block -->

                        <ul class="cus-info">
                           <li><span>User Name:</span> {{$result->user_name}}</li>
                           <li><span>Lead Name:</span> {{$result->lead_name}}</li>
                           <li><span>Email:</span> {{$result->email}}</li>
                           <li><span>Contact No.:</span> {{$result->phone}}</li>
                           <li><span>Date:</span> {{date('d F Y',strtotime($result->created_at))}}</li>
                           <li><span>Time:</span> {{date('h:i A',strtotime($result->created_at))}}</li>
                           <li><span>Message:</span> <span class="cus-message">{{$result->message}}</span></li>
                           <li><span>Depot:</span> {{$result->depot}}</li>
                           <li><span>Hitch:</span> {{$result->hitch}}</li>
                           <li><span>Buckets:</span> {{$result->buckets}}</li>
                           <li><span>Extra:</span> {{$result->extra}}</li>
                           <?php if(!empty($result->attachment)){ 
                              $ext = explode('.', $result->attachment);
                              ?>
                              <li><span>Attachment:</span> 
                                 <a class="" href="{{ asset('/public/admin/clip-one/assets/quotes')}}/{{ $result->attachment }}" target="_blank" downlaod><span>{{$result->attachment}}</span></a>
                                 <i class="far fa-file-{{$icons[$ext[1]]}} fa-5x text-center"/></i>
                              </li>
                           <?php } ?>
                           <li><span>Serial Number:</span> <span class="cus-message">{{$result->serial_number}}</span></li>
                           <li><span>Payment Confirm:</span> 
                              @if($result->payment_confirm == '1')
                                 <span class="cus-message btn btn-success">Yes</span>
                              @else
                                 <span class="cus-message btn btn-danger">No</span>
                              @endif
                           </li>
                           <li><span>PDI Status:</span> 
                              @if($result->PDI_status == '1')
                                 <span class="cus-message btn btn-success">Approved</span>
                              @else
                                 <span class="cus-message btn btn-danger">Defected</span>
                              @endif
                           </li>
                           <li><span>PDI Message:</span> <span class="cus-message">{{$result->PDI_message}}</span></li>
                           <li><span>Delivered:</span> 
                              @if($result->delivered == '1')
                                 <span class="cus-message btn btn-success">Yes</span>
                              @else
                                 <span class="cus-message btn btn-danger">No</span>
                              @endif
                           </li>
                           <li><span>Price:</span> <span class="cus-message">{{number_format($result->price,2)}}</span></li>
                           <li><span>Tax:</span> <span class="cus-message">{{$result->tax}}</span></li>
                           <li><span>Total Price:</span> <span class="cus-price">&#163;{{number_format($result->total_price,2)}}</span></li>
                        </ul>
                     </div>
                  </div>
               </div>

               <div class="row">
                  <div class="col-md-12">

                  </div>
               </div>

            </div>
            
          </div>

          <div class="card-footer">
              <div>
                 <a href="{{route('sales_order_report.index')}}" class="btn btn-primary btn-secondary float-sm-right">Back</a>
              </div>
           </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

@endsection

@section('script')

@endsection