<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'contact_no',
        'role_id',
        'facebook_id',
        'google_id',
        'avatar',
        'contact_verified_at',
        'avatar_type',
        'contact_code',
        'contact_verify_status',
        'two_way_auth',
        'contact_country',
        'street_address1',
        'street_address2',
        'city',
        'state',
        'country',
        'sell_to_friend',
        'sell_to_friend_of_friend',
        'sell_to_neighbour',
        'sell_to_uk',
        'lend_to_friend',
        'lend_to_friend_of_friend',
        'lend_to_neighbour',
        'lend_to_uk',
        'order_status',
        'message_status',
        'friend_status',
        'collect_status',
        'collection_status',
        'inpost_status',
        'lat',
        'lng',
        'location',
        'timezone',
        'box_preference',
        'reject_count'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*
    Relations 
    */
    public function userRole()
    {
        return $this->belongsTo('App\Role', 'role_id','id');
    }


    /* function sendsms($mobile_to, $mobile_from, $message)
      // {

      //     If neither or the user didn't supply any other core data, return an error
      //     #die($mobile_from);

      //     if (

      //         (!strlen($mobile_to) > 0)
      //         ||
      //         (!strlen($mobile_from) > 0)
      //         ||
      //         (!strlen($message) > 0)
      //     ) {
      //         return "Something went wrong with sending this message";
      //     }

      //     ## validate for sending ##
      //     $message_to_send = str_replace(" ", "%20", $message);
      //     $message_to_send = str_replace("&", "&amp;", $message_to_send);

      //     #die($sql);
      //     // Infobip's POST URL
      //     $postUrl = "http://api2.infobip.com/api/sendsms/xml";
      //     // XML-formatted data
      //     $xmlString =
      //         "<SMS>
              // <authentification>
              // <username>jukebox1</username>
              // <password>jukebox2</password>
              // </authentification>
              // <message>
              // <sender>" . $mobile_from . "</sender>
              // <text>" . $message_to_send . "</text>

              //  <flash></flash>
              //  <type>longSMS</type>
              //  <bookmark></bookmark>
              //  <binary></binary>
              //  <datacoding></datacoding>
              //  <esmclass></esmclass>
              //  <srcton></srcton>
              //  <srcnpi></srcnpi>
              //  <destton></destton>
              //  <destnpi></destnpi>
                
              // </message>
              // <recipients>
              // <gsm messageId=\"1000\">0044" . (substr($mobile_to, 1)) . "</gsm>
              // </recipients>
              // </SMS>";
      //     // previously formatted XML data becomes value of "XML" POST variable
      //     $fields = "XML=" . urlencode($xmlString);
      //     // in this example, POST request was made using PHP's CURL
      //     $ch = curl_init();
      //     curl_setopt($ch, CURLOPT_URL, $postUrl);
      //     curl_setopt($ch, CURLOPT_POST, 1);
      //     curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
      //     // response of the POST request
      //     $response = curl_exec($ch);
      //     curl_close($ch);


      //     if ($response > 0) {
      //       echo "SMS message sent";
      //     } else {
      //        echo "SMS message not sent";
      //     }
    } */

    function sendsms($mobile_to, $mobile_from, $message) {
        //echo "hello!";die;
        /*$curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://api2.infobip.com/sms/2/text/single",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "{ \"from\":\"Contact25\", \"to\":\"7968150172\", \"text\":\"Test SMS.\" }",
          CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "authorization: Basic anVrZWJveDE6anVrZWJveDI=",
            "content-type: application/json"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        }*/


        // die('sda');
    
        /*
        * If neither or the user didn't supply any other core data, return an error 
        */
        #die($mobile_from);
    
        if  (
            
            (!strlen($mobile_to)>0)
          ||
            (!strlen($mobile_from)>0)
          ||
            (!strlen($message)>0)
          )
        {
          return "Something went wrong with sending this message";
        }
        
        ## validate for sending ##
        $message_to_send = str_replace(" ", "%20", $message);
        $message_to_send = str_replace("&", "&amp;", $message_to_send);
        
        #die($sql);

        //  anVrZWJveDE6anVrZWJveDI=
        // Infobip's POST URL
        $postUrl = "http://api2.infobip.com/api/sendsms/xml";
        // XML-formatted data
        $xmlString =
        "<SMS>
        <authentification>
        <username>jukebox1</username>
        <password>jukebox2</password>
        </authentification>
        <message>
        <sender>".$mobile_from."</sender>
        <text>".$message_to_send."</text>

          <flash></flash>
          <type>longSMS</type>
          <bookmark></bookmark>
          <binary></binary>
          <datacoding></datacoding>
          <esmclass></esmclass>
          <srcton></srcton>
          <srcnpi></srcnpi>
          <destton></destton>
          <destnpi></destnpi>
          
        </message>
        <recipients>
        <gsm messageId=\"1000\">0044".(substr( $mobile_to, 1 ))."</gsm>
        </recipients>
        </SMS>";
        // previously formatted XML data becomes value of "XML" POST variable
        $fields = "XML=" . urlencode($xmlString);
        // in this example, POST request was made using PHP's CURL
        // dd($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $postUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "accept: application/json",
            "authorization: Basic anVrZWJveDE6anVrZWJveDI=",
            "content-type: application/json"
        ));
        // response of the POST request
        // $response = curl_exec($ch);
        // curl_close($ch);
        
        // dd($response);
        
        // if ($response>0){
        //   return "SMS message sent to<br />";
        // }else{
        //   return "SMS message not sent to<br />";
        // }

        $response = curl_exec($ch);
        $err = curl_error($ch);

        curl_close($ch);
        print_r($response);die;
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        }
        die('--------');
    }
    
    function sendText($mobile_to, $mobile_from, $message){
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://kxv6x.api.infobip.com/sms/2/text/single",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{ \"from\":\"".$mobile_from."\", \"to\":\"".$mobile_to."\", \"text\":\"".$message."\" }",
        CURLOPT_HTTPHEADER => array(
        "accept: application/json",
        "authorization: Basic anVrZWJveDE6anVrZWJveDI=",
        "content-type: application/json"
        ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        $response['success'] = 0;
        $response['message'] = $err;
        return response()->json($response);
        //echo "cURL Error #:" . $err;

      }
    }
    public function friend()
    {
       return $this->hasMany('App\friend','id','friend_id_1');
    }
    public function friends()
    {
      return $this->hasMany('App\friend','id','friend_id_2');
    }


}

  
    
