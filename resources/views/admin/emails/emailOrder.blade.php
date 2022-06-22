@extends('front.emails.emailMaster')
@section('content')
<div class="row" id="divID">
    <div class="col-md-12">
        <span>Hello,</span><br>
        <p>Your order request has been accepted, Please find order details below: </p>
      <div class="tbl">
        <table id="customers" class="table table-bordered table-striped table-hover" style="border: solid #d6d6d6 2px; padding: 10px;">

              <tr>
                <td colspan="2"><img src="{{url('/public/frontend/assets/AustralianFish/n-speed/image/logo.png')}}" class="img-fluid" width="100%"></td>
            </tr>

            <tr style="background-color: #ccc;">
              <td style="padding: 10px;">Name</td>
              <td style="padding: 10px;">{{ $orderDetails->billing_first_name }} {{ $orderDetails->billing_last_name }}</td>
            </tr>

            <tr>
              <td style="padding: 10px;">Phone</td>
              <td style="padding: 10px;">{{ $orderDetails->billing_phone }}</td>
            </tr>

            <tr style="background-color: #ccc;">
              <td style="padding: 10px;">Email</td>
              <td style="padding: 10px;">{{ $orderDetails->billing_email }}</td>
            </tr>

            <tr>
              <td style="padding: 10px;">Address</td>
              <td style="padding: 10px;">
                  {{ $orderDetails->billing_address }}, 
                {{ $orderDetails->billing_city }}, {{ $orderDetails->billing_country }}
              </td>
            </tr>

            <tr style="background-color: #ccc;">
              <td style="padding: 10px;">Billing Amount</td>
              <td style="padding: 10px;">A${{ $billamout }}</td>
            </tr>

            <tr>
              <td style="padding: 10px;">Billing Order Id</td>
              <td style="padding: 10px;">{{ $orderDetails->order_id }}</td>
            </tr>
        </table><br>
        
        
        <table id="customers" class="table table-bordered table-striped table-hover" style="width: 100%; border: solid #d6d6d6 2px; padding: 10px;">
            <tr style="background-color: #ccc;">
              <th style="padding: 10px;">Items</th>
              <th style="padding: 10px;">Quantity</th>
              <th style="padding: 10px;">Cost</th>
              <th style="padding: 10px;">Sub Total</th>
            </tr>

            @foreach ($itemsDetail as $item)
            <?php 
              $checkProduct = DB::table('products')->where('product_title',$item['item_name'])->first();
              if ($checkProduct->producttype_id == 1){
                $unitPrice = $item->item_price * 100;
              }else{
                $unitPrice = $item->item_price;
              } 
             ?>

            <tr>
              <td style="padding: 10px;text-align:center">{{ $item->item_name }}</td>
              <td style="padding: 10px;text-align:center">{{ $item->quantity }}</td>
              <td style="padding: 10px;text-align:center">A${{ $unitPrice }}</td>
              <td style="padding: 10px;text-align:center">A${{ $item->quantity * $unitPrice }}</td>
            </tr>
            @endforeach

        </table>
      </div>

      <div class="pull-right delivr" style="margin-top: 15px; margin-bottom: 15px; float: right;">
        <table id="customers" class="table table-bordered table-striped table-hover" style="border: solid #d6d6d6 2px; padding: 10px;"> 
          <tbody>
            <tr style="background-color: #ccc;">
              <td style="padding: 10px;"><strong>Delivery Charge </strong></td>
              <td style="padding: 10px;"> <strong>A${{$delivery_charge}} </strong></td> 
            </tr>
            <tr style="background-color: #ccc;">
              <td style="padding: 10px;"><strong>Total </strong></td>
              <td style="padding: 10px;"> <strong>A${{$billamout}} </strong></td> 
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @stop