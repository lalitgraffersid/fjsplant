<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Validator;
use Input;
use Auth;
use Cookie;
use Session;
use DB;
use Image;
use File;
use Exception;
use Mail;
use App\User;
use App\Models\Lead;
use App\Models\LeadComment;
use App\Models\Quote;
use App\Models\QuoteProduct;
use App\Models\SalesOrder;
use App\Models\Product;
use App\Models\ExtraProductInfo;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Dealer;
use App\Models\Customer;
use App\Helpers\AdminHelper;
use Carbon;
use Illuminate\Support\Facades\Storage;
use Response;
use PDF;

class QuoteController extends Controller
{
    /*Create New Quote with old lead*/
    public function createQuoteWithExistingLead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //'product_id' => 'required',
            //'quantity' => 'required',
            'lead_id' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(array(
                                        'status' => 400,
                                        'message'=> 'Error',
                                        'error_message'=>$validator->errors()
                                    ),200);
        } else {
            $lead = Lead::where('id',$request->lead_id)->first();
            $data = new Quote;
            //=========================================================
            $data->lead_id = $request->lead_id;
            $data->price = $request->price;
            $data->customer_id = $request->customer_id;
            $data->date = date('Y-m-d');

            if ($data->save()) {
                $newQtProduct = new QuoteProduct;
                
                $newQtProduct->quote_id = $data->id;
                $newQtProduct->product_id = $request->product_id;
                $newQtProduct->price = $request->price;
                $newQtProduct->quantity = $request->quantity;
                $newQtProduct->total_price = $request->quantity * $request->price;
                
                $newQtProduct->save();
            
                return response()->json(array(
                                            'status' => 200,
                                            'message'=> 'Success',
                                            'success_message'=>'Quote created successfully.',
                                            'data' => $data,
                                        ),200);
            }else{
                return response()->json(array(
                                            'status' => 400,
                                            'message'=> 'Error',
                                            'error_message'=>'Something went wrong!'
                                        ),200);
            }
        }
    }

    /*Create New Quote with new lead*/
    public function createQuoteWithNewLead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(array(
                                        'status' => 400,
                                        'message'=> 'Error',
                                        'error_message'=>$validator->errors()
                                    ),200);
        } else {
            $customerData = new Customer;
            $customerData->name = $request->name;
            $customerData->address = $request->address;
            $customerData->save();

            $newLead = new Lead;
            $newLead->title = $request->title;
            $newLead->name = $request->name;
            $newLead->phone = $request->phone;
            $newLead->address = $request->address;
            $newLead->date = date('Y-m-d');
            $newLead->status = 'In Progress';
            $newLead->message = $request->message;
            $newLead->user_id = $request->user_id;
            $newLead->save();

            // $product_array = json_decode($request->product_id);
            // $quantity_array = json_decode($request->quantity);

            // $price = 0;
            // foreach ($product_array as $key => $value) {
            //     $product = Product::where('id',$value)->first();
            //     $price += $product->price * $quantity_array[$key]; 
            // }

            // $product_id = implode(',',json_decode($request->product_id));
            // $quantity = implode(',',json_decode($request->quantity));

            $data = new Quote;
            //=========================================================
            // $data->product_id = $product_id;
            // $data->quantity = $quantity;
            $data->lead_id = $newLead->id;
            $data->customer_id = $customerData->id;
            $data->date = date('Y-m-d');
            // $data->price = $price;

            if ($data->save()) {
                return response()->json(array(
                                            'status' => 200,
                                            'message'=> 'Success',
                                            'success_message'=>'Quote created successfully.',
                                            'data' => $data,
                                        ),200);
            }else{
                return response()->json(array(
                                            'status' => 400,
                                            'message'=> 'Error',
                                            'error_message'=>'Something went wrong!'
                                        ),200);
            }
        }
    }

    /*Add To Quote*/
    public function addToQuote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quote_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(array(
                                        'status' => 400,
                                        'message'=> 'Error',
                                        'error_message'=>$validator->errors()
                                    ),200);
        } else {
            $product_array = json_decode($request->product_id);
            $quantity_array = json_decode($request->quantity);

            foreach ($product_array as $key => $value) {
                $checkProductExist = QuoteProduct::where('quote_id',$request->quote_id)
                                        ->where('product_id',$value)
                                        ->first();
                $product1 = Product::where('id',$value)->first();

                if (!empty($checkProductExist)) {
                    $data->quote_id = $request->quote_id;
                    $data->price = $product1->price;
                    $data->total_price = $quantity_array[$key] * $product1->price;
                    $data->save();
                }else{
                    $data = new QuoteProduct;
                    $data->product_id = $value;
                    $data->price = $product1->price;
                    $data->save();
                }
            }
            $price = QuoteProduct::where('quote_id',$request->quote_id)->sum('total_price');

            $quote = Quote::find($request->quote_id);
            $quote->price = $price;
            if ($quote->save()) {
                return response()->json(array(
                                            'status' => 200,
                                            'message'=> 'Success',
                                            'success_message'=>'Added to quote successfully.',
                                            'data' => $quote,
                                        ),200);
            }else{
                return response()->json(array(
                                            'status' => 400,
                                            'message'=> 'Error',
                                            'error_message'=>'Something went wrong!'
                                        ),200);
            }
        }
    }

    /*Quote List of Users*/
    public function userQuotes(Request $request)
    {
        if (!empty($request->search)) {

            $query = Quote::join('leads','quotes.lead_id','=','leads.id');

            if (!empty($request->search)) {
                $search = $request->search;
                $query = $query->where('leads.name','LIKE','%'.$search.'%');
            }
                        
            $quotes = $query->select('quotes.*','leads.name','leads.email','leads.phone','leads.user_id','leads.date','leads.status')
                        ->orderBy('quotes.id','DESC')
                        ->get();
        }else{
            $quotes = Quote::join('leads','quotes.lead_id','=','leads.id')
                        ->select('quotes.*','leads.name','leads.email','leads.phone','leads.user_id','leads.date','leads.status')
                        ->orderBy('quotes.id','DESC')
                        ->get();
        }
        
        $data = [];
        foreach($quotes as $quote){
            $checkSalesOrder = SalesOrder::where('quote_id',$quote->id)->first();

            if (empty($checkSalesOrder)) {
                $data[] = [
                    'id' => $quote->id,
                    //'products' => $this->getProducts($quote->product_id),
                    'lead_id' => $quote->lead_id,
                    'attachment' => $quote->attachment,
                    'price' => $quote->price,
                    'user_id' => $quote->user_id,
                    'lead_date' => $quote->date,
                    'lead_status' => $quote->status,
                    'sent' => $quote->sent,
                    'sent_on' => $quote->sent_on,
                ];
            }
        }

        if (count($data)>0) {
            return response()->json(array(
                        'status' => 200,
                        'message'=> 'Success',
                        'success_message'=>'Data found.',
                        'product_image_path' => url('/public/admin/clip-one/assets/products/original/'),
                        'quote_attachment_path' => url('/public/admin/clip-one/assets/quotes/'),
                        'data' => $data
                    ),200);
        }else{
            return response()->json(array(
                        'status' => 400,
                        'message'=> 'Error',
                        'error_message'=>'No data found!'
                    ),200);
        }
    }
    
    public function getProducts($id)
    {
        $products = QuoteProduct::where('quote_id',$id)->get();
        
        $data = [];
        foreach($products as $key => $product){
            $product_data = Product::join('product_images','products.id','=','product_images.product_id')
                                ->where('products.id',$product->product_id)
                                ->select('products.id as product_id','products.category_id','products.dealer_id','products.title','products.price as product_price','products.type','products.status as product_status','product_images.image','products.attachment as product_attachment')
                                ->first();

            $data[] = [
                'title' => $product_data->title,
                'product_price' => $product->price,
                'quantity' => $product->quantity,
                'total_price' => $product->total_price,
                'product_attachment' => $product_data->product_attachment,
            ];
        }
        
        return $data;
    }
    
    /*Quote Details*/
    public function quoteDetails(Request $request)
    {
        $quote = Quote::join('leads','quotes.lead_id','=','leads.id')
                        ->select('quotes.*','leads.name','leads.email','leads.phone','leads.address','leads.user_id','leads.date','leads.status')
                        ->where('quotes.id',$request->quote_id)
                        ->first();
        
        $data = [];
        $data[] = [
                'id' => $quote->id,
                'products' => $this->getProducts($quote->id),
                'lead_id' => $quote->lead_id,
                'attachment' => $quote->attachment,
                'price' => $quote->price,
                'lead_name' => $quote->name,
                'lead_email' => $quote->email,
                'lead_phone' => $quote->phone,
                'lead_address' => $quote->address,
                'user_id' => $quote->user_id,
                'lead_date' => $quote->date,
                'lead_status' => $quote->status,
                'sent' => $quote->sent,
                'sent_on' => $quote->sent_on,
            ];
        

        if (count($data)>0) {
            return response()->json(array(
                        'status' => 200,
                        'message'=> 'Success',
                        'success_message'=>'Data found.',
                        'product_image_path' => url('/public/admin/clip-one/assets/products/original/'),
                        'quote_attachment_path' => url('/public/admin/clip-one/assets/quotes/'),
                        'data' => $data
                    ),200);
        }else{
            return response()->json(array(
                        'status' => 400,
                        'message'=> 'Error',
                        'error_message'=>'No data found!'
                    ),200);
        }
    }

    /*Upload Quote attachment*/
    public function editQuote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quote_id' => 'required',
            'quote_product_id' => 'required',
            'product_price' => 'required',
            'quantity' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(array(
                                        'status' => 400,
                                        'message'=> 'Error',
                                        'error_message'=>$validator->errors()
                                    ),200);
        } else {
            $data = Quote::find($request->quote_id);
            //=========================================================
            $attachment = $request->file('attachment');

            if(!empty($attachment)) {
                $attachment_name = $attachment->getClientOriginalName();
                $attach_name = str_replace(' ', '',$attachment_name);

                $destinationPath = public_path('/admin/clip-one/assets/quotes');
                $attachment->move($destinationPath, $attach_name);
            } else {
                $attach_name = $data->attachment;
            }
            $data->attachment = $attach_name;

            $product_price_array = json_decode($request->product_price);

            foreach ($product_array as $key => $value) {
                $quote_product->price = $product_price_array[$key];
                $quote_product->quantity = $quantity_array[$key];
                $quote_product->save();
            }
            $final_price = QuoteProduct::where('quote_id',$request->quote_id)->sum('total_price');

            $data->price = $final_price;
            
            if(!empty($attach_name) && $attach_name != ''){
                $pathToFile = url('/public/admin/clip-one/assets/quotes').'/'.$attach_name;
            }else{
                $pathToFile = '';
            }

            if ($data->save()) {
                return response()->json(array(
                                            'status' => 200,
                                            'message'=> 'Success',
                                            'success_message'=>'Quote updated successfully.',
                                            'pathToFile'=>$pathToFile,
                                            'data' => $data,
                                        ),200);
            }else{
                return response()->json(array(
                                            'status' => 400,
                                            'message'=> 'Error',
                                            'error_message'=>'Something went wrong!'
                                        ),200);
            }
        }
    }


    /*Email Quotes*/
    public function sendQuote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quote_id' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(array(
                                        'status' => 400,
                                        'message'=> 'Error',
                                        'error_message'=>$validator->errors()
                                    ),200);
        } else {
            $data = Quote::find($request->quote_id);
            $data->sent = '1';
            $data->sent_on = date('Y-m-d');
            
            if(!empty($data->attachment)){
                $pathToFile = public_path().'/admin/clip-one/assets/quotes/'.$data->attachment;
            }else{
                $pathToFile = '';
            }

            $lead = Lead::where('id',$data->lead_id)->first();
            $user = User::where('id',$lead->user_id)->first();
            $customer = Customer::where('id',$data->customer_id)->first();
            $products = QuoteProduct::where('quote_id',$request->quote_id)->get();

            if (!empty($data)) {
                $leadData = Lead::find($data->lead_id);
                $emailData = array(
                    'email' => $leadData->email,
                    'title' => 'FJS Plant Quotation',
                    'pathToFile' => $pathToFile,
                    'quote' => $data,
                    'customer' => $customer,
                    'products' => $products,
                    'user_email' => $user->email,
                    'users' => $user,
                );
                
                if(!empty($data->attachment)){
                    Mail::send('api.emails.emalQuot', $emailData, function ($message) use ($emailData) {
                        $message->from('info@fjsplant.ie', 'FJS Plant Quotation');
                        $message->to($emailData['email']);
                        $message->cc([$emailData['user_email'],'lorraine@fjsplant.ie']);
                        $message->subject('FJS Plant Quotation');
                        $message->attach($emailData['pathToFile']);
                    });   
                }else{
                    Mail::send('api.emails.emaiQuote', $emailData, function ($message) use ($emailData) {
                        $message->from('info@fjsplant.ie', 'FJS Plant Quotation');
                        $message->to($emailData['email']);
                        $message->cc([$emailData['user_email'],'lorraine@fjsplant.ie']);
                        $message->subject('FJS Plant Quotation');
                    });
                }
                
                $data->save();

                // if( count(Mail::failures()) > 0 ) {
                //     echo "There was one or more failures. They were: <br />";
                //     foreach(Mail::failures() as $email_address) {
                //       echo " - $email_address <br />";
                //     }die;
                // } else {
                //     Log::info('Working');
                //     echo "No errors, all sent successfully!";
                //     die;
                // }

                return response()->json(array(
                                            'status' => 200,
                                            'message'=> 'Success',
                                            'success_message'=>'Quote sent successfully.',
                                            'pathToFile' => $pathToFile,
                                            'data' => $data,
                                        ),200);
            }else{
                return response()->json(array(
                                            'status' => 400,
                                            'message'=> 'Error',
                                            'error_message'=>'Something went wrong!!'
                                        ),200);
            }
        }
    }
    
    /*Download quote attachment*/
    public function downloadAttachment(Request $request)
    {
        $product = Product::find($request->product_id);
        $attachment = $product->attachment;
        $file = public_path().'/admin/clip-one/assets/products/attachment/'.$product->attachment;
        $headers = array('Content-Type: *');
        
        return Response::download($file, $attachment, $headers); 
    }

    /*Remove product from quote*/
    public function removeProductFromQuote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'quote_product_id' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(array(
                                        'status' => 400,
                                        'message'=> 'Error',
                                        'error_message'=>$validator->errors()
                                    ),200);
        } else {
            $product_array = json_decode($request->quote_product_id);

            $price = 0;
            foreach ($product_array as $key => $value) {
                ExtraProductInfo::where('quote_id',$request->id)->where('product_id',$quote_product->product_id)->delete();
                QuoteProduct::where('id',$value)->delete(); 
            }
            $final_price = QuoteProduct::where('quote_id',$request->id)->sum('total_price');

            $data->price = $final_price;

            if ($data->save()) {
                return response()->json(array(
                                            'status' => 200,
                                            'message'=> 'Success',
                                            'success_message'=>'Product removed from quote.',
                                            'data' => $data,
                                        ),200);
            }else{
                return response()->json(array(
                                            'status' => 400,
                                            'message'=> 'Error',
                                            'error_message'=>'Something went wrong!'
                                        ),200);
            }
        }
    }

    /*Preview quote mail*/
    public function previewQuoteMail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quote_id' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(array(
                                        'status' => 400,
                                        'message'=> 'Error',
                                        'error_message'=>$validator->errors()
                                    ),200);
        } else {
            
            if(!empty($data->attachment)){
                $pathToFile = public_path().'/admin/clip-one/assets/quotes/'.$data->attachment;
            }else{
                $pathToFile = '';
            }

            $lead = Lead::where('id',$data->lead_id)->first();
            $user = User::where('id',$lead->user_id)->first();
            $customer = Customer::where('id',$data->customer_id)->first();
            $products = QuoteProduct::where('quote_id',$request->quote_id)->get();

            if (!empty($data)) {
                $emailData = array(
                    'email' => $leadData->email,
                    'title' => 'FJS Plant Quotation',
                    'pathToFile' => $pathToFile,
                    'quote' => $data,
                    'customer' => $customer,
                    'products' => $products,
                    'user_email' => $user->email,
                    'users' => $user,
                );

                $pdf = PDF::loadView('api.emails.emailQuote', $emailData);

                echo "<pre>";
                print_r($pdf);die;

                return $pdf->download('quote.pdf');
                
                if(!empty($data->attachment)){
                    Mail::send('api.emails.emailQuote', $emailData, function ($message) use ($emailData) {
                        $message->from('info@fjsplant.ie', 'FJS Plant Quotation');
                        //$message->to($emailData['user_email']);
                        $message->to('vikas.nagar@commediait.com');
                        $message->subject('FJS Plant Quotation');
                        $message->attach($emailData['pathToFile']);
                    });   
                }else{
                    Mail::send('api.emails.emailQuote', $emailData, function ($message) use ($emailData) {
                        $message->from('info@fjsplant.ie', 'FJS Plant Quotation');
                        //$message->to($emailData['user_email']);
                        $message->to('vikas.nagar@commediait.com');
                        $message->subject('FJS Plant Quotation');
                    });
                }

                if( count(Mail::failures()) > 0 ) {
                    echo "There was one or more failures. They were: <br />";
                    foreach(Mail::failures() as $email_address) {
                      echo " - $email_address <br />";
                    }die;
                } else {
                    Log::info('Working');
                    echo "No errors, all sent successfully!";
                    die;
                }

                return response()->json(array(
                                            'status' => 200,
                                            'message'=> 'Success',
                                            'success_message'=>'Quote sent successfully.',
                                            'pathToFile' => $pathToFile,
                                            'data' => $data,
                                        ),200);
            }else{
                return response()->json(array(
                                            'status' => 400,
                                            'message'=> 'Error',
                                            'error_message'=>'Something went wrong!!'
                                        ),200);
            }
        }
    }
    public function inProgressQuote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(array(
                                        'status' => 400,
                                        'message'=> 'Error',
                                        'error_message'=>$validator->errors()
                                    ),200);
        } 
        $query = Quote::join('leads','quotes.lead_id','=','leads.id');
        
        $quotes = $query->select('quotes.*','leads.name','leads.email','leads.phone','leads.user_id','leads.date as lead_date','leads.status')
                        ->where('leads.user_id',$request->user_id)
                        ->where('quotes.sent','0')
                        ->get();
        
          return response()->json(array(
                                        'status' => 200,
                                        'message'=> 'Success',
                                        'success_message'=>'Get In Progress Quote.',
                                        'data' => $quotes,
                                    ),200);
    }
    public function sentQuote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(array(
                                        'status' => 400,
                                        'message'=> 'Error',
                                        'error_message'=>$validator->errors()
                                    ),200);
        } 
        
        $query = Quote::join('leads','quotes.lead_id','=','leads.id')
                        ->join('quote_products','quote_products.quote_id','=','quotes.id');
                        ->join('products','products.id','=','quote_products.product_id');
        
        $quotes = $query->select('quotes.*','leads.name','leads.email','leads.phone','leads.user_id','leads.date as lead_date','leads.status')
                        ->where('leads.user_id',$request->user_id)
                        ->where('quotes.sent','1')
                        ->get();
        
        return response()->json(array(
                                        'status' => 200,
                                        'message'=> 'Success',
                                        'success_message'=>'Get Sent Quote.',
                                        'data' => $quotes,
                                    ),200);
                                    
                                    Product::join('dealers','products.dealer_id','=','dealers.id')
                            ->join('categories','products.category_id','=','categories.id')
                            ->select('products.category_name as category_name','products.dealer_name as dealer_name','products.dealer_id as dealer_id','products.title','products.model','dealers.name as dealer_name','categories.name as category_name')
                            ->where('products.type','Used')
                            ->get();
                                        
    }

}