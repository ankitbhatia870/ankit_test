<?php
class Notificationhelper {

    /**
     * Apple Push Notification Services (APNS)
     *
     * @param   string  device_token
     * @param   string  message
     * @return  string  status  success|failure
     */
     public static function iphonePushNotification($device_token, $msg = null) {

        
        // Set private key's passphrase & local cert file path
        $passphrase = PASSPHRASE;
        $local_cert = LOCAL_CERT_PATH;
        $streamContext = stream_context_create();
        stream_context_set_option($streamContext, 'ssl', 'local_cert', $local_cert);
        stream_context_set_option($streamContext, 'ssl', 'passphrase', $passphrase);
        $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $error, $errorString, 20, STREAM_CLIENT_CONNECT, $streamContext);
        // Open a connection to the APNS server

        if (!$fp) {
                
            $status = "failure";
            
        } else {
            
                // iPhone Device Token Without Any Spaces
                $device_token = str_replace(' ', '', $device_token);

                // Set badge
                $badge = 1;

                // Check if device token not empty
                if (!empty($device_token)) {
                    // Create the payload body
                    $body['aps'] = array('alert' => $msg, 'badge' => $badge, 'sound' => 'default');

                    // Encode the payload as JSON
                    $payload = json_encode($body);
                    try {

                        // Build the binary notification
                        $msg = chr(0) . pack('n', 32) . pack('H*', $device_token) . pack('n', strlen($payload)) . $payload;

                        // Send it to the server
                        $result = fwrite($fp, $msg, strlen($msg));
                        
                        Log::info("ios result ::". var_export($result,TRUE) );
                        

                    } catch(Exception $e) {
                        //echo "1. Exception: " . $e -> getMessage() . "<br />\n";
                        Log::info("ios error ::". $e -> getMessage() );
                    }

                }
            
            $status = 'success';
        }
        fclose($fp);

        Log::info("returning  ::". $status );
        
        return $status;
     
    }
    




    #======================================================================================
    /**
     * Android Push Notifications using Google Cloud Messaging (GCM)
     * Send push notification from web server to registered android devices.
     *
     * @param   array   data
     * @param   string  message
     * @param   array   custom_fields
     * @return  string  status
     */
    public static function androidPushNotification ($deviceToken){
        // Set parameters:
        $apiKey = 'AIzaSyBgiOAHXJRTCbehKUprmqFS89ZuHZ0vTJQ'; // API key from Google Developer Console
        $gcmUrl = 'https://android.googleapis.com/gcm/send';
        $setMessage='You have a new activity on KarmaCircles.';
        // Get the device token (fetch from database for example):
        $regid  = $deviceToken;
    
        // Set message:
        $message = $setMessage;
    
        // Send message:
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $gcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                'Authorization: key=' . $apiKey,
                'Content-Type: application/json'
            )
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(
                array(
                    'registration_ids' => array($regid),
                    'data' => array(
                        'message' => $message
                    )
                ),
                JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
            )
        );
    
        $result = curl_exec($ch);
        Log::info("curl result ::". var_export($result,TRUE) );
         
        if ($result === FALSE){
            $status = 'failed: ' . curl_error($ch);
        }
        else
        {
            $status = 'success';
        }

        // Close connection
        curl_close($ch);
        Log::info("returning  ::". $status );

        return $status;
    }


}//end class
