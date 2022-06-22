<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Email</title> 
</head>

<body>
   <table style="width: 100%; margin: 0 auto; font-family: sans-serif;" border="0">
      <tr>
         <td colspan="2" align="center" valign="middle" style="padding:5px 0;">
            <a href="#" target="_blank"><img src="{{asset('assets/front/images/logo.png')}}" alt="" border="0" style="margin:0 auto;display: block; padding: 0;" width="100px"></a>
         </td> 
      </tr>
      <tr style="background-color: #f1f1f1;">
         <td colspan="2" style="padding: 16px; color: #333; letter-spacing: .4px;">
            <p style="margin: 0 0 4px 0;">{{date('d F Y',strtotime($quote->sent_on))}}</p>
            <p style="margin: 0 0 4px 0;">Re: Quotation</p>
            <p style="margin: 0 0 4px 0;">Dear {{$customer->name}},</p>
         </td>
      </tr>

      <tr style="background-color: #eaeaea;">
         <td colspan="2" style="padding: 16px; color: #333; letter-spacing: .4px;">As discussed, Please find quotation details below,</td>
      </tr>

      @foreach($products as $key => $product)
         <?php
            $productData = DB::table('products')->where('id',$product)->first();
         ?>
         <tr>
            <td style="padding: 20px 0 0 0; color: #333; letter-spacing: .4px;">
               <p style="margin: 0; color: #f11512; font-weight: 600;"> <i>{{$quantities[$key]}} x {{$productData->title}}</i> </p> 
               <br>
               {!! $productData->description !!}
            </td>
            <td style="padding: 20px 0 0 0; color: #333; letter-spacing: .4px; width: 170px;" valign="top">
               <p style="color: #f11512; margin: 0; font-weight: 600;"> <i>€ {{number_format($quantities[$key] * $productData->price,2)}} plus vat</i> </p>
            </td>
         </tr>
      @endforeach

      <tr>
         <td colspan="2" style="color: #333; letter-spacing: .4px;">
            <p style="color: #f11512; margin: 20px 0 5px 0; font-weight: 600;"> <i>Quotation valid for 21 days only</i> </p>
         </td>
      </tr>

     <tr>
         <td colspan="2" style="color: #333; letter-spacing: .4px;">If you have any queries or require any further information please do not hesitate to contact me on 086 603 9007 alternatively on email <a href="mailto:frank@fjsplant.ie">frank@fjsplant.ie</a> </td>
      </tr>

      <tr>
         <td colspan="2" style="color: #333; letter-spacing: .4px;">
            <p style="margin: 20px 0 6px 0; font-weight: 600;">Regards</p>
            <p style="background-color: #a5a5a5; height: 1px; width: 200px; margin: 9px 0 9px 0;"></p>
            <p style="margin: 0 0 6px 0; font-weight: 600;">Frand Smyth</p>
            <p style="margin: 0 0 6px 0; font-weight: 600;">FJS Plant Repairs Ltd</p>    
         </td>

         <tr>
            <td colspan="2" style="color: #333; letter-spacing: .4px; text-align: center;">
               <p style="line-height: 1.65; background-color: #f1f1f1; padding: 20px 0; margin: 20px 0 0 0;">
                  <b>FJS Repairs Ltd, Timahoe, Donadea, Naas, Co. Kildare <br>
                         Tel: 00353 45 863542 / enquiries@fjsplant.ie </b>
               </p>
            </td>
         </tr>
      </tr>
        
   </table>

</body>
</html>
