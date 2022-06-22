
   <footer class="main-footer">
      <strong>Copyright &copy; 2020-2021 <a href="#">FJS Plant</a>.</strong>
      All rights reserved.
      <div class="float-right d-none d-sm-inline-block">
         <b>Version</b> 3.0.5
      </div>
   </footer>

   <aside class="control-sidebar control-sidebar-dark">
   </aside>
</div>

<!-- jQuery -->
<script src="{{asset('assets/admin/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/inputmask/jquery.inputmask.min.js')}}"></script>
<!-- DataTables  & Plugins -->
<script src="{{asset('assets/admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/admin/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('assets/admin/dist/js/adminlte.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('assets/admin/dist/js/demo.js')}}"></script>
<script src="{{asset('assets/admin/plugins/toastr/toastr.min.js')}}"></script>

<!--<script src="{{asset('/vendor/yajra/laravel-datatables-buttons/src/resources/assets/buttons.server-side.js')}}"></script>-->
<script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
<script src="{{asset('assets/admin/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>

<script type="text/javascript">
   @if(Session::has('message'))
      var type = "{{ Session::get('alert-type', 'info') }}";
      switch(type){
         case 'info':
            toastr.info("{{ Session::get('message') }}");
            break;

         case 'warning':
            toastr.warning("{{ Session::get('message') }}");
            break;

         case 'success':
            toastr.success("{{ Session::get('message') }}");
            break;
                
         case 'error':
            toastr.error("{{ Session::get('message') }}");
            break;
      }
   @endif
</script>

<script type="text/javascript">
   $('.add_more').on('click',function(){
      var id = $(this).data('id');

      $('#product_id').val(id);
   });

   $("#stock_quantityA").on('keyup',function(){
      var qty = $("#stock_quantityA").val();

      if (qty > 1) {
         $('.addedRowA').remove();

         for(i=1; i<qty; i++) {
            $("#addDivA").append('<div class="row addedRowA" style="margin-top: -30px;"><div class="col-md-6"></div><div class="col-md-6"><div class="form-group"><label for="stock_number"></label><input type="text" name="stock_number[]" class="form-control stock_number" id="stock_number" placeholder="Serial Number" required></div></div></div>');
         }
      }else{
         $('.addedRowA').remove();
      }
   });
</script>

</body>
</html>