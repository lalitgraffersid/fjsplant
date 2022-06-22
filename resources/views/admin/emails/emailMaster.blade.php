<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>{{$title}}</title>
<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700" rel="stylesheet">

</head>

<body style="margin:0; padding:0;">
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--<tr>-->
  <!--  <td align="center" valign="middle" style="padding:5px 0;">-->
  <!--    <a href="#" target="_blank"><img src="{{url('/public/frontend/assets/AustralianFish/n-speed/image/logo.png')}}" class="img-fluid" width="100%" alt="" border="0" style="display:inline-block; margin:0; padding: 0;"></a>-->
  <!--  </td>-->
  <!--</tr>-->
  <tr>
      <td>
        @yield('content')
      </td>
  </tr>
  <tr>
    <td align="center" valign="top" style="background:#243f8f; padding:15px 0; font-family:'Arial Black', Gadget, sans-serif; font-size:12px; line-height:15px; font-weight:300; color:#fff;">Copyright © - <?php echo date('Y');?> NE Game. All rights reserved.</td>
  </tr>
</table>
</body>
</html>