@extends('front.layout.master')
@section('content')

<style>
.service-before{ 
    background-image: url(https://fjsplant.ie/assets/front/images/about-bg.jpg);
    background-color: transparent;
    position: relative;
    z-index: 1;
}
.service-before:before{
    content: "";
    display: block;
    background-color: #ffffffd1;
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    z-index: -1;
}
</style>

<section>
	<div class="container-fluid">
		<div class="row">
			<!--<div class="inner-header" style="background-image: url({{asset('assets/front/images/services-banner.jpg')}}"></div>-->
			<div class="col-lg-12 p-0">
			    <video loop="true" autoplay="autoplay" muted="" playsinline="" style="height: 500px;">
				    <source src="{{asset('assets/front/images/services.mp4')}}" type="video/mp4">
    		    </video>
			</div>
		</div>
	</div>
</section>	


<section>
	<div class="container-fluid" style="padding: 0px;
    margin: 0px;">
		<div class="row">
			<div class="services-panel service-before">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
							<div class="services-box service-form">
							    <h1>Book a Service</h1>
							    <form id="quickForm" action="{{route('request.service')}}" method="POST">
							    	{{csrf_field()}}
								    <input type="text" name="name" class="form-control" placeholder="Name">
								    <input type="email" name="email" class="form-control" placeholder="Email">
								    <textarea name="address" rows="6" class="form-control" placeholder="Address"></textarea>
								    <input type="text" name="make" class="form-control" placeholder="Make">
								    <input type="text" name="model" class="form-control" placeholder="Model">
								    <input type="text" name="serial_number" class="form-control" placeholder="Serial Number">
								    <select name="request_type" class="form-control">
								        <option value="">-- Select --</option>
								        <option value="Service">Service</option>
								        <option value="Repair">Repair</option>
								    </select>
								    <textarea name="issue" class="form-control" rows="6" placeholder="Issues"></textarea>
								    <button type="submit">Submit</button>
								    
									<!--<div class="web-content">-->
									<!--	<h1>Service</h1>-->
									<!--	<p>Our Customer and Product Support at FJS Plant is second to none. We currently have a fleet of mobile vehicles on the road offering a back up service to all of our customers.</p>-->
										
									<!--	<p>This along with our workshop facility at our depot ensures our customers that all service requirements are carried out to a high standard.</p>-->
										
									<!--	<p>We have a dedicated qualified team of plant fitters to keep your machines up and running 24/7.</p>-->
										
									<!--	<p>In 2018 FJS Plant Repairs were awarded the Service Excellance Reward from Kubota Construction Machiney, Pictured recieving the Award from Gary Walsh Service Manager (Kubota UK) was Frank Smyth (Director/FJS Plant Repairs Ltd).</p>-->
									<!--</div>-->
							    </form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<section class="top-space">
	<div class="container-xxl container-xl container-md container-sm">
		<div class="tz-gallery">

            <div class="row">
    			 <div class="col-xl-12 col-lg-12 text-center mb-3">
    				<!--<div class="web-content">-->
    				<!--    <h2>Machine Gallery</h2>-->
    				<!--</div>-->
    			</div>
    			
                @foreach($galleries as $gallery)
                <div class="col-md-4 col-sm-6">
                    <a class="lightbox" href="{{url('/admin/clip-one/assets/gallery/original')}}/{{$gallery->image}}">
                        <img src="{{url('/admin/clip-one/assets/gallery/original')}}/{{$gallery->image}}" alt="Park">
                    </a>
                </div>
                @endforeach

            </div>

        </div>
		
	</div>
</section>

@endsection

@section('script')

<script src="{{asset('assets/admin/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/jquery-validation/additional-methods.min.js')}}"></script>

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
         	address: {
            	required: true
         	},
         	make: {
            	required: true
         	},
         	model: {
            	required: true
         	},
         	serial_number: {
            	required: true
         	},
         	request_type: {
            	required: true
         	}
      	},
      	messages: {
        	name: {
            	required: "Please enter Name!",
         	},
         	email: {
            	required: "Please enter email!",
         	},
         	address: {
            	required: "Please enter address!",
         	},
         	make: {
            	required: "Please enter make!",
         	},
         	model: {
            	required: "Please enter model!",
         	},
         	serial_number: {
            	required: "Please enter serial number!",
         	},
         	request_type: {
            	required: "Please select request type!",
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
@endsection