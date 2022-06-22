@extends('admin.layout.master')
@section('content')

<style>
.user-block img {
    float: left;
    height: 80px;
    width: 80px;
    object-fit: cover;
    margin: 0 10px 0 0;
}

.user-block .username {
    margin: 20px 0 0 0;
}
ul.cus-info{
    list-style-type: none;
    padding: 6px;
    height: auto;
}
ul.cus-info li{
    font-size: 16px;
    margin: 0 0 10px 0;
    list-style-type: none;
    padding: 0 0 0 0;
}
ul.cus-info li span{
    display: inline-block;
    width: 130px;
    vertical-align: top;
}
ul.cus-info li span.cus-message{
    width: 80%;
    height: auto;
}

</style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User Details</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">User Details</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <!-- <div class="card-header">
          <h3 class="card-title">Projects Detail</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div> -->
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
              <div class="row">
                <div class="col-12">
                  <h4></h4>
                    <div class="post">
                      <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="{{$image}}" alt="">
                        <span class="username">
                          <a href="#">{{$result->name}}</a>
                        </span>
                        <span class="description">Joined On - {{date('d F Y',strtotime($result->created_at))}}</span>
                      </div>
                      <!-- /.user-block -->

                      <ul class="cus-info">
                          <li><span>Email:</span> {{$result->email}}</li>
                          <li><span>Contact No.:</span> {{$result->mobile}}</li>
                      </ul>
                    </div>

                </div>
              </div>
            </div>
            
          </div>

          <div class="card-footer">
              <div>
                 <a href="{{route('users.index')}}" class="btn btn-primary btn-secondary float-sm-right">Back</a>
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