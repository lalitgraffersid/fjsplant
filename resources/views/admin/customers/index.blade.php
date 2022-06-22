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
               <h1 class="m-0 text-dark">Customers</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                  <li class="breadcrumb-item active">Customers List</li>
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
                        <a href="{{route('customers.add')}}" class="btn btn-info float-right"><i class="fas fa-plus"></i> {{$data['actionData']->action_title}} </a>
                        <a href="{{route('customers.import')}}" class="btn btn-info float-right" style="margin-right: 5px;"><i class="fas fa-file-import"></i> Import </a>
                     </div>
                  @endif

                  <div class="card-body">
                     {!! $dataTable->table(['class'=>'table dataTable no-footer']) !!}
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

@endsection
  


