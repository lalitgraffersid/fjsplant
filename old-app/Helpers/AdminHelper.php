<?php

namespace App\Helpers;

use App\Models\AdminPermission;
use App\Models\Role;
use App\Models\Action;
use App\Models\Section;
use DB;
use DateTime;

class AdminHelper
{
	public static function getRoleSection($user_id,$section_id)
	{
		$permissions =  AdminPermission::where('user_id',$user_id)->get();

		$data = [];
		foreach ($permissions as $key => $value) {
			$data[] = Role::where('id',$value->role_id)->where('section_id',$section_id)->first();
		}
		
		return $data;
	}

	public static function getAdminPermissions($user_id,$role_id)
	{
		$data =  AdminPermission::where('user_id',$user_id)->where('role_id',$role_id)->first();
		
		return $data;
	}

	public static function checkAddButtonPermission($section,$user_id)
	{
		$data['actionData'] = Action::where('action_slug','add')->first();

   		$data['checkRole'] = Role::where('name_slug',$section)->whereRaw("find_in_set('".$data['actionData']->id."',action_id)")->first();

   		$data['checkPermission'] = AdminPermission::join('roles','admin_permissions.role_id','=','roles.id')
                        ->select('admin_permissions.id as id','admin_permissions.user_id','admin_permissions.role_id','admin_permissions.action_id','roles.name','roles.name_slug','roles.url')
                        ->where('roles.name_slug',$section)
                        ->where('admin_permissions.user_id',$user_id)
                        ->whereRaw("find_in_set('add',admin_permissions.action_id)")
                        ->first();
		
		return $data;
	}

	public static function compress_image($source_url, $destination_url, $quality) {

        $info = getimagesize($source_url);

        if ($info['mime'] == 'image/jpeg'){
            $image = imagecreatefromjpeg($source_url);
        }else if ($info['mime'] == 'image/gif'){
            $image = imagecreatefromgif($source_url);
        }else if ($info['mime'] == 'image/png'){
            $image = imagecreatefrompng($source_url);
        }

        imagejpeg($image, $destination_url, $quality);
        
        return $destination_url;
    }


    public static function time_elapsed_string($datetime, $full = false) 
    {
	    $now = new DateTime;
	    $ago = new DateTime($datetime);
	    $diff = $now->diff($ago);

	    $diff->w = floor($diff->d / 7);
	    $diff->d -= $diff->w * 7;

	    $string = array(
	        'y' => 'year',
	        'm' => 'month',
	        'w' => 'week',
	        'd' => 'day',
	        'h' => 'hour',
	        'i' => 'min',
	        's' => 'sec',
	    );
	    foreach ($string as $k => &$v) {
	        if ($diff->$k) {
	            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
	        } else {
	            unset($string[$k]);
	        }
	    }

	    if (!$full) $string = array_slice($string, 0, 1);
	    return $string ? implode(', ', $string) . ' ago' : 'just now';
	}

	public static function push_notification($device_id,$message)
    {
        //API URL of FCM
        $url = 'https://fcm.googleapis.com/fcm/send';
        $api_key = 'AAAAm1nOfsw:APA91bGWc-ON07XC5EEPzgRJr1ek_Za9TH-gVWhg6BfYfrdVbGh9JXhEcsbBIZXWHY7L9TBMsZKaMi7aUiXC869DeQgGjfIeMp7VEjAJWhBI4_prDb0x2jMQ9SC2htwofBiWNOG4D9fW';
                    
        $notification = array('title' => 'New Lead' , 'body' => $message);
        $fields = array(
            'to' => $device_id,
            'data' => $message = array(
                "message" => $message
            ),
            'notification' => $notification
        );

        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$api_key
        );
                    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            echo ('FCM Send Error: ' . curl_error($ch));
        }

        echo "<pre>";
        print_r($result);die;
        
        curl_close($ch);
        // return $result;
    }

    public static function removeHtmlTags($content)
    {
        $data = strip_tags($content);
        $data = str_replace('&nbsp;', ' ', $data);

        return $data;
    }

    public static function csvToArray($filename = '', $delimiter = ','){
        
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }

}