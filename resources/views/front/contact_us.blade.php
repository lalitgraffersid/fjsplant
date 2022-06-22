@extends('front.layout.master')
@section('content')

<section>
	<div class="container-fluid">
		<div class="row">
			<div class="inner-header">&nbsp;</div>
		</div>
	</div>
</section>



<section class="contact-icons contact-bg">
	<div class="container-xxl container-xl container-md container-sm">
		<div class="row">
			<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 offset-md-2">
				<div class="contact-panel">
				  
				  
				
				    
					<h1>Contact Form</h1>
					
					<form id="quickForm" action="{{route('contact.save')}}" method="POST">
						{{csrf_field()}}
						<input type="text" name="name" id="name" class="form-control" placeholder="Name">
						<input type="email" name="email" id="email" class="form-control" placeholder="Email">
						<input type="text" name="subject" id="subject" class="form-control" placeholder="Subject">
						<input type="text" name="contact_no" class="form-control" id="contact_no" placeholder="Contact No.">
						<textarea rows="5" name="message" id="message" class="form-control" placeholder="Message"></textarea>
						 
						 	@if(config('services.recaptcha.key'))
                            <div class="g-recaptcha"
                                data-sitekey="{{config('services.recaptcha.key')}}">
                            </div>
                        @endif
						
						<button type="submit">Submit</button>
					</form>

				</div>
				
					@if ($success == "contactform")
                  <div class = "alert alert-success" style="margin: 0px 40px;">
                    <ul style="padding: 0px;margin: 0px;">
                      <li> Thank you for your message. It has been sent.</li>
                    </ul>
                  </div>
                @endif
                
                @if ($error == "errorContact")
                  <div class = "alert alert-success" style="margin: 0px 40px;">
                    <ul style="padding: 0px;margin: 0px;">
                      <li> Something went wrong!</li>
                    </ul>
                  </div>
                @endif


				<div class="contact-info web-content mt-5">
					<h2>Contact Info</h2>
					<p>It is business as usual here at FJS Plant Repairs. We will be operating with government guidelines taking into consideration. For any enquiries you can contact our sales team direct; Ken – 087 787 8327 Kieran – 087 168 7751</p>



					

					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12">
					<ul class="contact-list">
						<li>Call: 00 353 (0)45 863542</li>
						<li>Email: <a href="mailto:enquiries@fjsplant.ie">enquiries@fjsplant.ie</a></li>
						<li>Address: Timahoe, Donadea, Naas Co. Kildare, W91 A789</li>
					</ul>
						</div>

						<div class="col-lg-6 col-md-6 col-sm-12">
					<ul class="contact-list">
						<li>Mon – Friday 8.30 to 17.30</li>
						<li>Saturday by appointment</li>
						<li>COVID Announcement – 21.10.2020</li>
					</ul>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid mt-5">
		<div class="row">
			<div class="col-md-12 p-0">
					<!--<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d19061.12394585453!2d-6.835891!3d53.33179!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xf3172107a35f035a!2sFJS%20Plant%20Repairs%20Limited!5e0!3m2!1sen!2sin!4v1614860282884!5m2!1sen!2sin" width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy"></iframe>-->
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
         	subject: {
            	required: true
         	},
         	subject: {
            	required: true
         	},
         	contact_no: {
            	required: true
         	},
         	message: {
            	required: true
         	},
      	},
      	messages: {
        	name: {
            	required: "Please enter Name",
         	},
         	email: {
            	required: "Please enter email",
         	},
         	subject: {
            	required: "Please enter subject",
         	},
         	contact_no: {
            	required: "Please enter contact number",
         	},
         	message: {
            	required: "Please enter message",
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