@extends('front.layout.master')
@section('content')

<style>
	.team-details h2{
		padding: 0;
    	text-align: left;
	}
</style>

<section>
	<div class="container-fluid">
		<div class="row">
			<div class="inner-header">&nbsp;</div>
		</div>
	</div>
</section>



<section>
	<div class="container-fluid">
		<div class="row">
			<div class="services-panel">
				<div class="container-xxl container-xl container-md container-sm">
					<div class="row">
						<div class="col-xl-12 text-center mb-2">
							<div class="web-content">
								<h1>Our Team</h1>
							</div>
						</div>

						@foreach($teams as $team)
							<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
								<div class="team-panel">
									<div class="team-icon">
										<img src="{{url('/admin/clip-one/assets/team/original')}}/{{$team->image}}" alt="" class="img-fluid d-block mx-auto">
									</div>

									<div class="team-details">
										<h1>{{$team->name}}</h1>
										<h2>{{$team->designation}}</h2>
										<p>{{$team->description}}</p>
									</div>
								</div>
							</div>
						@endforeach

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