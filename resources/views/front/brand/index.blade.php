@extends('front.layout.master')
@section('content')


<section>
	<div class="container-fluid">
		<div class="row">
			<div class="slider-bg">
				<div class="row">
					<div class="col-lg-12 p-0">

						<div class="slider-panel">
							@if($dealer->type == '1')
							    <iframe src="{!! $dealer->video_url !!}" class="inner-video" width="100%" height="800" frameborder="0" allowfullscreen></iframe>
							@else
								<video loop="true" autoplay="autoplay" muted playsinline class="inner-video">
									<source src="{{url('/admin/clip-one/assets/dealers/video_file/')}}/{{$dealer->video_file}}" type="video/mp4">
								</video>
							@endif
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section>
	<div class="container-fluid">
		<div class="row">
			<div class="services-panel">
				<div class="container-xxl container-xl container-md container-sm">
					<div class="row">
						<div class="col-xl-9 col-lg-9 col-md-9 col-sm-12">
							<div class="row">
								<div class="col-xl-12 col-lg-12 col-md-12">
									<div class="web-content">
										<h1>{{$dealer->name}}</h1>
									</div>
								</div>
								
								@if(count($results)>0)
									@foreach($results as $result)
									<?php 
										$product_image = DB::table('product_images')->where('product_id',$result->id)->first();
									?>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
										<div class="product-box">
											<div class="product-icon stock-icon">
												@if(!empty($product_image->image))
													<img src="{{url('/admin/clip-one/assets/products/original')}}/{{$product_image->image}}" alt="" class="img-fluid d-block mx-auto">
												@else
	                                       			<img src="{{url('/assets/no_image.jpg')}}" alt="" class="img-fluid d-block mx-auto">
	                                    		@endif
											</div>
											
											<div class="product-text web-content stock-box">
												<h3>{{$result->title}}</h3>
												<a href="{{route('productDetails',$result->id)}}">Read More</a>
											</div>
										</div>
									</div>
									@endforeach
								@else
									<p>No Result Found!</p>
								@endif

							</div>
						</div>
						
						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
							<div style="position: sticky; top: 0;">
								<div class="web-content">
									<h2>Brands</h2>
								</div>
								<div class="stock-sidebar">
									<ul class="stock-list">
										@foreach($dealers as $dealer)
										<li><a href="{{route('brand',$dealer->name)}}">{{$dealer->name}}</a></li>
										@endforeach
									</ul>																
								</div>
								<a href="{{route('contact_us')}}" class="stock-contact">Contact Us</a>
								
								<div class="web-content mt-4">
									<p>FJS Plant was established in 1993 we are based approximately 25 miles from Dublin city centre. FJS Plant currently employ 22 skilled and highly experienced Staff across the area of Sales, Service, Repair, Spare Parts, Marketing and Administration. They are now a market leader in the Sales, Supply and Service of mobile plant and demolition equipment in Ireland.</p>
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
<script>
	$(document).ready(function(){
		$('#footer_class').removeClass();
	});
</script>
@endsection