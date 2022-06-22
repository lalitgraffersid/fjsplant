@extends('front.layout.master')
@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.green.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.10.0/css/lightbox.min.css">

<section>
	<div class="container-fluid">
		<div class="row">
			<div class="services-panel">
				<div class="container-xxl container-xl container-md container-sm">
					<div class="row">
						<div class="col-xl-7 col-lg-7 col-md-7 col-sm-12">
							<div class="detail-panel">
								<div class="pro-detail-box mb-4">
									@if(!empty($product_images[0]->image))
										<img src="{{url('/public/admin/clip-one/assets/products/original')}}/{{$product_images[0]->image}}" alt="" class="img-fluid d-block mx-auto">
									@else
                               			<img src="{{url('/assets/no_image.jpg')}}" alt="" class="img-fluid d-block mx-auto">
                            		@endif
								</div>


								<div class="aligner">
									<div class="owl-carousel owl-theme">
										@if(count($product_images)>0)
											@foreach($product_images as $product_image)
												<div class="item">
													<a href="{{url('/public/admin/clip-one/assets/products/original')}}/{{$product_image->image}}" data-lightbox="gallery">
														<img src="{{url('/public/admin/clip-one/assets/products/original')}}/{{$product_image->image}}" alt="">
													</a>
												</div>
											@endforeach
										@endif	
									</div>
								</div>
							</div>
						</div>

						<div class="col-xl-5 col-lg-5 col-md-5 col-sm-12">
							<div class="machine-details">
								<div class="web-content">
								<h1>{{$result->title}}</h1>
								{!! $result->description !!}

								<!--<h2 class="machine-cost mt-4">Price: <span><i class="icofont-pound"></i>{{$result->price}}</span></h2>-->
							</div>

							<h3 class="machine-details">Machine Details</h3>
							<ul class="details-list">
									<li><span class="machine-title">Make:</span> <span>{{$result->make}}</span></li>
									<li><span class="machine-title">Model:</span> <span>{{$result->model}}</span></li>
								</ul>

								<ul class="button-list">
									<li><a href="{{route('contact_us')}}">Enquiry</a></li>
									@if(!empty($result->attachment))
									    <li><a href="{{ asset('/public/admin/clip-one/assets/products/attachment')}}/{{ $result->attachment }}" target="_blank" download="{{ $result->attachment }}">Download Attachment <i class="icofont-download"></i></a></li>
									@endif
								</ul>

								<div class="social-sharebox">
									<h4 class="machine-details">Social Sharing</h4>
									<ul class="ftr-socials detail-social">
										<li><a href="https://www.facebook.com/fjsplant/" target="_blank"><i class="icofont-facebook"></i></a></li>
										<li><a href="https://www.linkedin.com/in/fjs-plant-a49869199/" target="_blank"><i class="icofont-linkedin"></i></a></li>
										<li><a href="https://www.instagram.com/fjsplant/" target="_blank"><i class="icofont-instagram"></i></a></li>
									</ul>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.10.0/js/lightbox.min.js"></script>
<script  src="{{asset('assets/front/js/owl.js')}}"></script>

<script>
	$(document).ready(function(){
		$('#footer_class').removeClass();
	});
</script>
@endsection