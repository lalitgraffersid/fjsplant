<?php 

$productTypes = DB::table('products')->select('type')->groupby('type')->get();
$brands = DB::table('dealers')->where('status','1')->orderBy('order_no')->get();

?>

<section>
    <div class="container-fluid">
        <div class="row">
            
            <div class="menu-panel">
				<div class="container-xxl container-xl container-md container-sm">
					<div class="row">
					    
					    <div class="col-xl-4 col-lg-4 col-md-4">
					        <div class="logo">
					            <a href="{{route('home')}}"><img src="{{asset('assets/front/images/logo.png')}}" alt=""></a>
					        </div>
					    </div>

						<div class="col-xl-5 col-lg-5 col-md-5">
							<ul class="ftr-links ftr-contact top-call">
									<li>Call: <a href="tel:+353(0)45863542">+353 (0)45 863542</a></li>
									<li>Email: <a href="mailto:enquiries@fjsplant.ie">enquiries@fjsplant.ie</a></li>
									<li><a style="display: block;    background-color: #da230f;
    color: #fff !important;    text-decoration: none;    text-align: center;    padding: 8px 15px 8px 15px;  font-size: 16px;    text-transform: uppercase;    letter-spacing: 3px;    border-radius: 5px;
    margin-top: 10px;    width: 100%;    border: none;   outline: none;" class="register-btn-header" href="https://www.eventbrite.com/e/fjs-open-day-tickets-324088576657?utm_source=eventbrite&utm_medium=email&utm_campaign=post_publish&utm_content=shortLinkNewEmail">Register For Event</a></li>
							</ul>
						</div>

						<div class="col-xl-3 col-lg-3 col-md-3">
								<div id="wrapper">
    
                                <!-- Sidebar -->
                            <nav class="navbar navbar-inverse fixed-top cus-fixed" id="sidebar-wrapper" role="navigation">
                                 <div class="sidebar-navigation">
                              <ul>
                              	<li><a href="{{route('home')}}">Home</a></li>
                              	<li><a href="#">Our Company <em class="mdi mdi-chevron-down"></em></a>
                              		<ul>
                                      <li><a href="{{route('about_us')}}">About Us</a></li>
                                      <!--<li><a href="{{route('our_team')}}">Our Team</a></li>-->
                                    </ul>
                              	</li>
                            
                                  <li><a href="#">Our Machines <em class="mdi mdi-chevron-down"></em></a>
                                      <ul>
                                          <li><a href="#">Brand <em class="mdi mdi-chevron-down"></em></a>
                                          <ul>
                                            @foreach($brands as $brand)
                                                <li><a href="{{route('brand',$brand->name)}}">{{$brand->name}}</a></li>
                                            @endforeach
                                          </ul>
                                          </li>
                            
                                          <li><a href="#">Stock <em class="mdi mdi-chevron-down"></em></a>
                                          <ul>
                                              @foreach($productTypes as $productType)
                                              <li><a href="{{route('productsFilter')}}?type={{$productType->type}}">{{$productType->type}} Machinery</a></li>
                                              @endforeach
                                          </ul>
                                          </li>
                            
                                      </ul>
                                  </li>
                                  <li><a href="{{route('parts')}}">Parts</a></li>
                                  <li><a href="{{route('services')}}">Service</a></li>
                                  <li><a href="{{route('news')}}">News</a></li>
                                  <li><a href="{{route('contact_us')}}">Contact</a></li>
                              </ul>
                            </div>
                              </nav>
                                <!-- /#sidebar-wrapper -->
                        
                                <!-- Page Content -->
                                <div id="page-content-wrapper">
                                    <button type="button" class="hamburger animated fadeInLeft is-closed" data-toggle="offcanvas">
                                        <i class="icofont-navigation-menu"></i> Menu
                                    </button>
                                    
                                    
                                </div>
                                <!-- /#page-content-wrapper -->
                        
                            </div>
						</div>
					</div>
				</div>
			</div>
            
            
        </div>
    </div>
</section>