@extends('admin.layout.master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Lead Details</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Lead Details</li>
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
                  <div class="col-md-12">
                     <h4></h4>
                     <div class="post">
                        <!-- /.user-block -->

                        <ul class="cus-info">
                           <li><span>Customer Name:</span> {{$result->name}}</li>
                           <li><span>Email:</span> {{$result->email}}</li>
                           <li><span>Contact No.:</span> {{$result->phone}}</li>
                           <li><span>Address:</span> {{$result->address}}</li>
                           <li><span>Date:</span> {{date('d F Y',strtotime($result->created_at))}}</li>
                           <li><span>Message:</span> <span class="cus-message">{{$result->message}}</span></li>
                        </ul>
                     </div>
                  </div>
               </div>

               <div class="row">
                  <div class="col-md-12">
                     <div class="card card-info">
                        <div class="card-header">Comment panel</div>
                           <div class="card-body">
                              <form action="{{ route('leads.comment') }}" method="POST">
                                 {{csrf_field()}}
                                 <input type="hidden" name="lead_id" value="{{ $result->id }}">
                                 <textarea name="comment" class="form-control" placeholder="write a comment..." rows="3"></textarea><br>
                                 <button type="submit" class="btn btn-info float-right">Post</button>
                                 <div class="clearfix"></div>
                              </form>
                              <hr>
                              
                              <ul class="media-list">
                              @foreach($comments as $comment)
                              <?php
                                 $user = DB::table('users')->where('id',$comment->comment_by)->first();
                                 $timeAgo = App\Helpers\AdminHelper::time_elapsed_string($comment->created_at);
                              ?>
                                 <li class="media">
                                     <a href="#" class="float-left">
                                         <img src="https://bootdey.com/img/Content/user_1.jpg" alt="" class="img-circle" style="width: 60px">
                                     </a>
                                     <div class="media-body">
                                         <span class="text-muted float-right">
                                             <small class="text-muted">{{$timeAgo}}</small>
                                         </span>
                                         <strong class="text-success">by {{$user->name != 'admin' ? $user->name : 'me'}}</strong>
                                         <p>{{$comment->comment}}</a>
                                         </p>
                                     </div>
                                 </li>
                                 <hr>
                              @endforeach

                             </ul>
                         </div>
                     </div>

                  </div>
               </div>

            </div>
            
          </div>

          <div class="card-footer">
              <div>
                 <a href="{{route('sales_calls_report.index')}}" class="btn btn-primary btn-secondary float-sm-right">Back</a>
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