@extends('front.layout.master')
@section('content')



<link rel="stylesheet" href="{{asset('assets/front/css/products.css')}}">


<style>
#overlay {
position: fixed;
top: 0;
left: 0;
width: 100%;
height: 100%;
background-color: #000;
filter:alpha(opacity=70);
-moz-opacity:0.7;
-khtml-opacity: 0.7;
opacity: 0.7;
z-index: 100;
display: none;
}
.cnt223 a{
text-decoration: none;
}
.popup-onload{
width: 100%;
margin: 0 auto;
display: none;
position: fixed;
z-index: 101;
    background-color: #000000c9;
    top: 0;
}
.cnt223{
width: 80%;
margin: 0px auto;
background: #f3f3f3;
position: relative;
z-index: 103;
padding: 15px 35px;
border-radius: 5px;
box-shadow: 0 2px 5px #000;
}
.cnt223 p{
clear: both;
    color: #555555;
    /* text-align: justify; */
    font-size: 20px;
    font-family: sans-serif;
}
.cnt223 p a{
color: #d91900;
font-weight: bold;
}
.cnt223 .x{
float: right;
height: 35px;
left: 22px;
position: relative;
top: -25px;
width: 34px;
}
.cnt223 .x:hover{
cursor: pointer;
}
.close{
background-color: #000;
    color: #fff !important;
    display: inline-block;
    width: 40px;
    height: 40px;
    text-align: center;
    line-height: 2.8;
}
.viewall-btn{
		background-color: #d33e47;
    color: #fff;
    text-decoration: none;
    display: table;
    margin: 0 auto;
    padding: 15px 40px;
    border-radius: 4px;
    font-size: 18px;
}
</style>


<!--<div class='popup-onload'>-->
<!--<div class='cnt223'>-->

<!--<a href='' class='close'>X</a>-->
<!--<img src="{{asset('assets/front/images/uc.jpg')}}" width="100%">-->
<!--</div>-->
<!--</div>-->


<section>
	<div class="container-fluid">
		<div class="row">
			<div class="slider-bg">
				<div class="row">
					<div class="col-lg-12 p-0">

						<div class="slider-panel">
						    
						    <video loop="true" autoplay="autoplay" muted playsinline>
								<source src="{{asset('assets/front/images/banner.mp4')}}" type="video/mp4">
							</video>
							
							
<!--							<div id="carouselExampleCaptions" class="carousel slide p-0" data-bs-ride="carousel">-->
  
	 
<!--  <div class="carousel-inner">-->
<!--    <div class="carousel-item active">-->
<!--      <img src="{{asset('assets/front/images/slider1.png')}}" class="img-fluid d-block mx-auto" alt="">-->
<!--    </div>-->

<!--    <div class="carousel-item">-->
<!--      <img src="{{asset('assets/front/images/slider2.png')}}" class="img-fluid d-block mx-auto" alt="">-->
<!--    </div>-->

<!--    <div class="carousel-item">-->
<!--      <img src="{{asset('assets/front/images/slider3.png')}}" class="img-fluid d-block mx-auto" alt="">-->
<!--    </div>-->
<!--  </div>-->

<!--  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"  data-bs-slide="prev">-->
<!--    <span class="carousel-control-prev-icon" aria-hidden="true"></span>-->
<!--    <span class="visually-hidden">Previous</span>-->
<!--  </button>-->
<!--  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"  data-bs-slide="next">-->
<!--    <span class="carousel-control-next-icon" aria-hidden="true"></span>-->
<!--    <span class="visually-hidden">Next</span>-->
<!--  </button>-->
<!--</div>-->
						</div>
					</div>
				</div>
			</div>
		</div>
</div>
</section>


<section class="filter-search">
	<div class="container-xxl container-xl container-md container-sm">
		<form action="{{route('productsFilter')}}" method="GET">
			<!-- {{csrf_field()}} -->
			<div>
				<div class="row">
					<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
						<select class="" name="type" id="type">
							<option value="">Choose Type</option>
							<option value="New">New</option>
							<option value="Used">Used</option>
						</select>
					</div>
						
					<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
						<select class="" name="dealer_id[]" id="dealer_id">
							<option value="">Choose Make</option>
							@foreach($dealers as $dealer)
								<option value="{{$dealer->id}}">{{$dealer->name}}</option>
							@endforeach
						</select>
					</div>
						
					<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
						<select class="" name="model[]" id="model">
							<option value="">Choose Make First</option>
						</select>
					</div>
						
					<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
						<button type="submit" id="filter_button"><i class="icofont-search-2"></i></button>
					</div>
					<span id="filter_error"></span>
				</div>
			</div>
		</form>
	</div>
</section>
	

	
<section class="home-about-panel">
	<div class="container-xxl container-xl container-md container-sm">
		<div class="row">
			<div class="col-xl-5 col-lg-5 col-md-6 col-sm-12">
				<div class="machine-section">
				    <img src="{{asset('assets/front/images/machine01.jpg')}}" alt="" class="img-fluid d-block mx-auto">
				</div>
			</div>
			
			<div class="col-xl-7 col-lg-7 col-md-6 col-sm-12">
				<div class="web-content"> <!--  about-section -->
					<h1>About Us</h1>
					<p>FJS  Plant offer a comprehensive range of machinery, we are the main dealer for Liugong Excavators & Loaders, Kubota Construction Equipment, Furuakawa FRD Rockbreakers, EvoQuip a Terex Brand of Demolition and Crushing Equipment and NC Engineering Dumpers. Whatever your requirements, whatever industry you’re in, you’ll find what you need at 	FJS  Plant from Sales, Service and Parts.</p>
					<a href="{{route('about_us')}}">Read More</a>
				</div>
			</div>
		</div>
	</div>
</section>


<section class="top-space">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="web-content">
					<!--<h1>Parts</h1>-->
				</div>
			</div>

			<div class="col-lg-3 col-md-3 col-sm-6">
				<div class="parts-wrappper">
					<div class="parts-icon">
					    <a href="https://fjsplant.com/brand/KUBOTA">
    						<img src="{{asset('assets/front/parts/Kubota.png')}}" alt="" class="img-fluid">
    						<div class="parts-text">
    							<h2>Kubota</h2>
    						</div>
    					</a>
					</div>
				</div>
			</div>

			<div class="col-lg-3 col-md-3 col-sm-6">
				<div class="parts-wrappper">
					<div class="parts-icon">
					    <a href="https://fjsplant.com/brand/Liugong">
    						<img src="{{asset('assets/front/parts/liugong.jpeg')}}" alt="" class="img-fluid">
    						<div class="parts-text">
    							<h2>Liugong</h2>
    						</div>
						</a>
					</div>
				</div>
			</div>

			<div class="col-lg-3 col-md-3 col-sm-6">
				<div class="parts-wrappper">
					<div class="parts-icon">
					    <a href="https://fjsplant.com/brand/FRD">
    						<img src="{{asset('assets/front/parts/FRD.jpeg')}}" alt="" class="img-fluid">
    						<div class="parts-text">
    							<h2>FRD</h2>
    						</div>
						</a>
					</div>
				</div>
			</div>

			<div class="col-lg-3 col-md-3 col-sm-6">
				<div class="parts-wrappper">
					<div class="parts-icon">
					    <a href="https://fjsplant.com/brand/Evoquip">
    						<img src="{{asset('assets/front/parts/Evoquip.jpeg')}}" alt="" class="img-fluid">
    						<div class="parts-text">
    							<h2>Evoquip</h2>
    						</div>
    					</a>
					</div>
				</div>
			</div>

			<div class="col-lg-3 col-md-3 col-sm-6">
				<div class="parts-wrappper">
					<div class="parts-icon">
					    <a href="https://fjsplant.com/brand/NC">
    						<img src="{{asset('assets/front/parts/NC.jpeg')}}" alt="" class="img-fluid">
    						<div class="parts-text">
    							<h2>NC</h2>
    						</div>
    					</a>
					</div>
				</div>
			</div>

			<div class="col-lg-3 col-md-3 col-sm-6">
				<div class="parts-wrappper">
					<div class="parts-icon">
					    <a href="https://fjsplant.com/brand/Robi">
    						<img src="{{asset('assets/front/parts/ROBI.jpeg')}}" alt="" class="img-fluid">
    						<div class="parts-text">
    							<h2>ROBI</h2>
    						</div>
    					</a>
					</div>
				</div>
			</div>

			<div class="col-lg-3 col-md-3 col-sm-6">
				<div class="parts-wrappper">
					<div class="parts-icon">
					    <a href="https://fjsplant.com/brand/DRESSTA">
    						<img src="{{asset('assets/front/parts/Dressta.png')}}" alt="" class="img-fluid">
    						<div class="parts-text">
    							<h2>Dressta</h2>
    						</div>
    					</a>
					</div>
				</div>
			</div>

			<div class="col-lg-3 col-md-3 col-sm-6">
				<div class="parts-wrappper">
					<div class="parts-icon">
					    <a href="https://quotesv2.finaldrives.com/FJS-Website/home?SlewRings=True&RecoilSprings=True&HydraulicPumps=True">
    						<img src="{{asset('assets/front/parts/fdc.jpg')}}" alt="" class="img-fluid">
    						<div class="parts-text">
    							<h2>FDC</h2>
    						</div>
    					</a>
					</div>
				</div>
			</div>

		

		</div>
	</div>
</section>


<section class="top-space">
	<div class="container-xxl container-xl container-md container-sm">
		<div class="row">
			<div class="col-lg-12 mb-4">
				<div class="web-content text-center">
					<h4>Brands</h4>
				</div>
			</div>
			
			<div class="col-lg-12">
    			<div class="customer-logos slider">
    				@foreach($dealers as $dealer)
      					<a href="{{route('brand',$dealer->name)}}">
      						<div class="slide">
      							<img src="{{url('/public/admin/clip-one/assets/dealers/original')}}/{{$dealer->image}}" alt=""></div>
      					</a>
      				@endforeach
   				</div>
			</div>
		</div>
	</div>
</section>
	
	
<!-- <section class="top-space">
	<div class="container-xxl container-xl container-md container-sm">
		<div class="row">
			<div class="col-lg-12">
				<div class="web-content text-center">
					<h4>Clients Testimonials</h4>
				</div>
			</div>
			
			
	<div class="col-lg-12">
	<div class="gtco-testimonials">
    <div class="owl-carousel owl-carousel1 owl-theme">
      <div>
        <div class="card text-center"><img class="card-img-top" src="{{asset('assets/front/images/testi.jpg')}}" alt="">
          <div class="card-body">
            <h5>Mick Fox</h5>
            <p class="card-text">“ Fast efficient and friendly ” </p>
          </div>
        </div>
      </div>
      <div>
        <div class="card text-center"><img class="card-img-top" src="{{asset('assets/front/images/testi.jpg')}}" alt="">
          <div class="card-body">
            <h5>Cathal Phibbs</h5>
            <p class="card-text">“ Excellent customer service, and quality products ” </p>
          </div>
        </div>
      </div>
      <div>
        <div class="card text-center"><img class="card-img-top" src="{{asset('assets/front/images/testi.jpg')}}" alt="">
          <div class="card-body">
            <h5>Paul Clare</h5>
            <p class="card-text">“ Purchased this girl 28 months ago and its has never missed a beat ” </p>
          </div>
        </div>
      </div>
		<div>
			<div class="card text-center"><img class="card-img-top" src="{{asset('assets/front/images/testi.jpg')}}" alt="">
          <div class="card-body">
            <h5>Sale Kildare</h5>
            <p class="card-text">“ Great service ” </p>
          </div>
        </div>
		</div>
        
		  
		<div>
			<div class="card text-center"><img class="card-img-top" src="{{asset('assets/front/images/testi.jpg')}}" alt="">
          <div class="card-body">
            <h5>Glenn Power</h5>
            <p class="card-text">“ Great staff and service ” </p>
          </div>
        </div>
		</div>
		  
		<div>
			<div class="card text-center"><img class="card-img-top" src="{{asset('assets/front/images/testi.jpg')}}" alt="">
          <div class="card-body">
            <h5>Finbarr Sullivan</h5>
            <p class="card-text">“ Been in here lately good people make a great business they looked after me really well 1 top roller and hub oil change very quick . Thanks guys and lovely girls in office cheers ” </p>
          </div>
        </div>
		</div>
		  
		<div>
			<div class="card text-center"><img class="card-img-top" src="{{asset('assets/front/images/testi.jpg')}}" alt="">
          <div class="card-body">
            <h5>KNR Turbines</h5>
            <p class="card-text">“ Excellent machines ” </p>
          </div>
        </div>
		</div> 
		 
		<div>
			<div class="card text-center"><img class="card-img-top" src="{{asset('assets/front/images/testi.jpg')}}" alt="">
          <div class="card-body">
            <h5>William Mullally</h5>
            <p class="card-text">“ Excellent ” </p>
          </div>
        </div>
		</div>
		  
    </div>
  </div>
			</div>
		</div>
	</div>
</section> -->

	
	



@endsection

@section('script')
<script  src="{{asset('assets/front/js/products.js')}}"></script>

<script>
	$('#filter_button').on('click',function(){
		var type = $('#type').val();
		var dealer_id = $('#dealer_id').val();
		var model = $('#model').val();

		if (type == '' && dealer_id == '' && model == '') {
			toastr.error('Please choose atleast one.', 'Error');
			return false;
		}
	});
</script>

<script>
	$('#dealer_id').on('change',function(){
		var dealer_id = $('#dealer_id').val();

		if (dealer_id != '') {
			$.ajax({
	            url: "{{ url('getModels') }}/"+dealer_id,
	            method: "get",
	            success: function (response) {
                    $('#model').html(response.data)
	            }
	        });
		}
	});
</script>

<script>
$(function(){
var overlay = $('<div id="overlay"></div>');
overlay.show();
overlay.appendTo(document.body);
$('.popup-onload').show();
$('.close').click(function(){
$('.popup-onload').hide();
overlay.appendTo(document.body).remove();
return false;
});


 

$('.x').click(function(){
$('.popup').hide();
overlay.appendTo(document.body).remove();
return false;
});
});</script>
@endsection