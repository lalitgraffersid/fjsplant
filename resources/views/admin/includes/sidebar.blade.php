<?php
   $current_route = \Request::route()->getName();
   $current_route_array = explode('.', $current_route);
   $checkRoute = DB::table('roles')->where('name_slug',$current_route_array[0])->first();

   $sections = DB::table('sections')->where('section_slug','!=','modules')->orderBy('section_order','ASC')->get();
?>

<style>
   [class*=sidebar-dark-] .nav-sidebar>.nav-item>.nav-treeview {
    padding: 0 0 0 30px;
    font-size: 13px;
}
.nav-treeview i{
    font-size: 9px;
    position: relative;
    top: -1px;
    margin: 0 2px 0 0;
}
.admin-logo{
    background-color: #fff;
    text-align: center;
    padding: 8px 0 10px 0;
}
.admin-logo img{
    width: 130px;
}
.sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active, .sidebar-light-primary .nav-sidebar > .nav-item > .nav-link.active {
    background-color: #f01310;
}
[class*='sidebar-dark-'] .nav-treeview > .nav-item > .nav-link.active, [class*='sidebar-dark-'] .nav-treeview > .nav-item > .nav-link.active:hover, [class*='sidebar-dark-'] .nav-treeview > .nav-item > .nav-link.active:focus{
    background-color: rgb(195 195 195);    
}
</style>

  
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
<!-- Brand Logo -->
       <div class="admin-logo">
           <a href="#">
            <img src="{{asset('assets/admin/dist/img/logo-fjs.png')}}" alt="AdminLTE Logo" class="brand-image">
            <span class="brand-text font-weight-light">&nbsp;</span>
          </a>
       </div>

   <!-- Sidebar -->
   <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
         <div class="info">
            <a href="#" class="d-block">Admin</a>
         </div>
      </div> -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
         <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item has-treeview">
               <a href="{{route('admin.dashboard')}}" class="nav-link <?php if($current_route == 'admin.dashboard'){echo 'active';} ?>"><i class="nav-icon fas fa-tachometer-alt"></i><p>
                Dashboard</p></a>
            </li>

            <!-- <li class="nav-item <?php if($current_route == 'actions.index' || $current_route == 'sections.index' || $current_route == 'roles.index' || $current_route == 'actions.add' || $current_route == 'actions.edit' || $current_route == 'sections.add' || $current_route == 'sections.edit' || $current_route == 'roles.add' || $current_route == 'roles.edit'){echo 'menu-open';} ?>">

               <a href="#" class="nav-link <?php if($current_route == 'actions.index' || $current_route == 'sections.index' || $current_route == 'roles.index' || $current_route == 'actions.add' || $current_route == 'actions.edit' || $current_route == 'sections.add' || $current_route == 'sections.edit' || $current_route == 'roles.add' || $current_route == 'roles.edit'){echo 'active';} ?>">
                  <i class="nav-icon fas fa-copy"></i>
                  <p>Modules<i class="fas fa-angle-left right"></i></p>
               </a>

               <ul class="nav nav-treeview">
                  <li class="nav-item">
                     <a href="{{ url('/admin/actions/index')}}" class="nav-link <?php if($current_route == 'actions.index' || $current_route == 'actions.add' || $current_route == 'actions.edit'){echo 'active';} ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Actions</p>
                     </a>
                  </li>
               </ul>
               <ul class="nav nav-treeview">
                  <li class="nav-item">
                     <a href="{{ url('/admin/sections/index')}}" class="nav-link <?php if($current_route == 'sections.index' || $current_route == 'sections.add' || $current_route == 'sections.edit'){echo 'active';} ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Sections</p>
                     </a>
                  </li>
               </ul>
               <ul class="nav nav-treeview">
                  <li class="nav-item">
                     <a href="{{ url('/admin/roles/index')}}" class="nav-link <?php if($current_route == 'roles.index' || $current_route == 'roles.add' || $current_route == 'roles.edit'){echo 'active';} ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Roles</p>
                     </a>
                  </li>
               </ul>
            </li> -->
            
            @foreach($sections as $section)
               <?php 
               $getRoleSection = App\Helpers\AdminHelper::getRoleSection(Auth::user()->id,$section->id);
               if (Auth::user()->user_type == 'admin' || !empty(array_filter($getRoleSection)) > 0) { ?>

                  <li class="nav-item <?php if(!empty($checkRoute) && $checkRoute->section_id == $section->id){echo 'menu-open';} ?>">
                     <a href="#" class="nav-link <?php if(!empty($checkRoute) && $checkRoute->section_id == $section->id){echo 'active';} ?>">
                        <i class="nav-icon fas fa-stream"></i>
                        <p>{{$section->section_title}}<i class="fas fa-angle-left right"></i></p>
                     </a>
                     <?php 
                     $roles=DB::table('roles')->where('section_id',$section->id)->orderBy('order','ASC')->get(); ?>
                
                     @if(!empty($roles))
                        @foreach($roles as $rol)
                           <?php 
                              $adminPermissions = App\Helpers\AdminHelper::getAdminPermissions(Auth::user()->id,$rol->id); 
                              if (Auth::user()->user_type == 'admin' || !empty($adminPermissions)) { ?>

                                 <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                       <a href="{{ route($rol->url)}}" class="nav-link <?php if(!empty($checkRoute) && $checkRoute->id == $rol->id){echo 'active';} ?>">
                                          <i class="fas fa-chevron-right"></i>
                                          <p>{{$rol->name}}</p>
                                       </a>
                                    </li>
                                 </ul>

                              <?php } ?>
                        @endforeach
                     @endif

                  </li>
               <?php } ?>
            @endforeach   

         </ul>
      </nav>
      <!-- /.sidebar-menu -->
   </div>
   <!-- /.sidebar -->
</aside>