@extends('front.layout.master')
@section('content')


<section class="mb-5">
	<div class="container-fluid">
		<div class="row">
			<div class="inner-header">&nbsp;</div>
		</div>
	</div>
</section>

<section>
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="web-content">
					<h1>Parts</h1>
				</div>
			</div>

			<div class="col-lg-3 col-md-3 col-sm-6">
				<div class="parts-wrappper">
					<div class="parts-icon">
						<img src="{{asset('assets/front/parts/Kubota.png')}}" alt="" class="img-fluid">
						<div class="parts-text">
							<h2>Kubota</h2>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-3 col-md-3 col-sm-6">
				<div class="parts-wrappper">
					<div class="parts-icon">
						<img src="{{asset('assets/front/parts/liugong.jpeg')}}" alt="" class="img-fluid">
						<div class="parts-text">
							<h2>Liugong</h2>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-3 col-md-3 col-sm-6">
				<div class="parts-wrappper">
					<div class="parts-icon">
						<img src="{{asset('assets/front/parts/FRD.jpeg')}}" alt="" class="img-fluid">
						<div class="parts-text">
							<h2>FRD</h2>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-3 col-md-3 col-sm-6">
				<div class="parts-wrappper">
					<div class="parts-icon">
						<img src="{{asset('assets/front/parts/Evoquip.jpeg')}}" alt="" class="img-fluid">
						<div class="parts-text">
							<h2>Evoquip</h2>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-3 col-md-3 col-sm-6">
				<div class="parts-wrappper">
					<div class="parts-icon">
						<img src="{{asset('assets/front/parts/NC.jpeg')}}" alt="" class="img-fluid">
						<div class="parts-text">
							<h2>NC</h2>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-3 col-md-3 col-sm-6">
				<div class="parts-wrappper">
					<div class="parts-icon">
						<img src="{{asset('assets/front/parts/ROBI.jpeg')}}" alt="" class="img-fluid">
						<div class="parts-text">
							<h2>ROBI</h2>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-3 col-md-3 col-sm-6">
				<div class="parts-wrappper">
					<div class="parts-icon">
						<img src="{{asset('assets/front/parts/Dressta.png')}}" alt="" class="img-fluid">
						<div class="parts-text">
							<h2>Dressta</h2>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-3 col-md-3 col-sm-6">
				<div class="parts-wrappper">
					<div class="parts-icon">
						<img src="{{asset('assets/front/parts/fdc.jpg')}}" alt="" class="img-fluid">
						<div class="parts-text">
							<h2>FDC</h2>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-12 mb-5">
				<a href="https://quotesv2.finaldrives.com/FJS-Website/home?SlewRings=True&RecoilSprings=True&HydraulicPumps=True" target="_blank"><img src="{{asset('assets/front/images/fdc-banner.jpg')}}" alt="" class="img-fluid"></a>
			</div>

			<div class="parts-contact-wrapper mb-5">
				<form id="quickForm" action="{{route('partsRequest')}}" method="POST">
					{{csrf_field()}}
					<div class="row">
						<div class="col-lg-10 offset-md-1">
							<div class="row">
								<div class="col-lg-12">
									<h3 class="partsform">Fill the form below</h3>
								</div>

								<div class="col-lg-6">
									<input type="text" name="name" id="name" placeholder="Name" class="form-control">
								</div>

								<div class="col-lg-6">
									<input type="number" name="contact_no" id="contact_no" class="form-control" placeholder="Contact Number">
								</div>

								<div class="col-lg-12">
									<input type="email" name="email" id="email" class="form-control" placeholder="Email">
								</div>

								<div class="col-lg-6">
									<select name="brand" id="brand" class="form-control">
										<option value="">-- Select Brand --</option>
										@foreach($brands as $value)
											<option value="{{$value->name}}" data-id="{{$value->id}}">{{$value->name}}</option>
										@endforeach
									</select>
								</div>

								<div class="col-lg-6">
									<select name="model" id="model" class="form-control">
										<option>-- Select Brand First --</option>
									</select>
								</div>

								<div class="col-lg-12">
									<textarea rows="6" name="message" placeholder="Message" id="message" class="form-control"></textarea>
								</div>

								<!-- div class="col-lg-12">
									<label class="tok-box"><input type="checkbox" name="consent" value="yes" id="consent" class="form-control">I consent</label>
								</div -->

								<div class="col-lg-12">
									<button type="submit">Submit</button>
								</div>

							</div>
						</div>
					</div>
				</form>
			</div>

		</div>
	</div>
</section>

@endsection

@section('script')
<script src="{{asset('assets/admin/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/jquery-validation/additional-methods.min.js')}}"></script>

<script type="text/javascript">
   $('#brand').on('change',function(){
      var id = $(this).val();

      $.ajax({
         url: "{{ url('getModels') }}"+"/"+id,
         method: "get",
         data: {},
         success: function (response) {
            //console.log(response); 
            $('#model').html(response);
         }
      });
   });
</script>

<script>
$(function () {
   	$('#quickForm').validate({
      	rules: {
         	name: {
            	required: true
         	},
         	email: {
            	required: true
         	},
         	contact_no: {
            	required: true
         	},
         	brand: {
            	required: true
         	},
         	model: {
            	required: true
         	},
         	message: {
            	required: true
         	},
         	consent: {
            	required: true
         	},
      	},
      	messages: {
        		name: {
            	required: "",
         	},
         	email: {
            	required: "",
         	},
         	contact_no: {
            	required: "",
         	},
         	brand: {
            	required: "",
         	},
         	model: {
            	required: "",
         	},
         	message: {
            	required: "",
         	},
         	consent: {
            	required: "",
         	},
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
         error.addClass('invalid-feedback');
         element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
         $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
         $(element).removeClass('is-invalid');
      }
   });
});
</script>

<script>
  $(document).ready(function(){
    $('#footer_class').removeClass();
  });
</script>
@endsection