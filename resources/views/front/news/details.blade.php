@extends('front.layout.master')
@section('content')

<section class="top-space">
	<div class="container-xxl container-xl container-md container-sm">
		<div class="row">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				<div class="web-content">
					<h1>News Details</h1>
				</div>

				<div class="blog-panel detail-panel">
					<div class="blog-box">
						<div class="blog-details-icon">
							<img src="{{url('/public/admin/clip-one/assets/news/original')}}/{{$result->image}}" alt="" class="img-fluid d-block mx-auto">
						</div>
						<div class="blogger">
							<!-- <span><img src="{{url('/public/admin/clip-one/assets/news/original')}}/{{$result->image}}" alt="" width="100"></span> -->
							<h2>{{$result->title}}</h2>
						</div>

						<div class="blog-date">
							<h3>{{date('d F Y',strtotime($result->created_at))}}</h3>
						</div>

						<div class="blog-content web-content">
							{!! $result->description !!}
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