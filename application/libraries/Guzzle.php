<?php

use GuzzleHttp\Client;


class Guzzle
{    

    protected $auth = 'c52204d23e5497155b0d96a1727d388a08c2331e4635317b37c772c193792c4a';
    protected $time = '20220620T075421Z';
    protected $key = 'NODEFLUX-HMAC-SHA256 Credential=C83YWDG6WLGNSZIV59322B5DC/20220620/nodeflux.api.v1beta1.ImageAnalytic/StreamImageAnalytic, SignedHeaders=x-nodeflux-timestamp, Signature=c52204d23e5497155b0d96a1727d388a08c2331e4635317b37c772c193792c4a';

    /**
     * Signature
     *
     * @param  mixed $params
     * @return void
     */
    public function signature($params)
    {
        $client = new Client();

        $response = $client->request('POST', 'https://backend.cloud.nodeflux.io/auth/signatures', [
            'body' => json_encode([
                'access_key' => $params['access_key'],
                'secret_key' => $params['secret_key'],
            ])

        ]);

        $data = $response->getBody();
        $read_json = json_decode($data, true);
        
        return ($read_json);
    }


    public function init($params){
        $client = new Client();

        $response = $client->request('POST', 'https://backend.cloud.nodeflux.io/auth/signatures', [
            'body' => json_encode([
                'access_key' => $params['access_key'],
                'secret_key' => $params['secret_key'],
            ])

        ]);

        $data = $response->getBody();
        $read_json  = json_decode($data, true);
        
        $timestamp  = $read_json['headers']['x-nodeflux-timestamp'];
        $time       = substr($timestamp, 0, 8);
        $token      = $read_json['token'];


        session_start();
        
        $_SESSION['timestamp'] = $timestamp;
        $_SESSION['token'] = $token;
        $_SESSION['auth_key'] = "NODEFLUX-HMAC-SHA256 Credential={$params['access_key']}/{$time}/nodeflux.api.v1beta1.ImageAnalytic/StreamImageAnalytic, SignedHeaders=x-nodeflux-timestamp, Signature={$token}";

        return ['status' => true, 'message' => 'successfuly getting authorization data and set them to session, now you can go to homepage and try face match'];
    }
    
    /**
     * face_match
     *
     * @param  mixed $params
     * @return void
     */
    public function face_match($params)
    {
        if(isset($_SESSION['auth_key']) && isset($_SESSION['timestamp'])){
            $client = new Client();

            $response = $client->request('POST', 'https://api.cloud.nodeflux.io/syncv2/analytics/face-match', [
                'headers' => [
                    'Authorization' => $_SESSION['auth_key'],
                    'x-nodeflux-timestamp' => $_SESSION['timestamp']
                ],
                'body' => json_encode([
                    'additional_params' => [
                        'similarity_threshold' => $params['threshold'],
                        'auto_orientation' => false
                    ],
                    'images' => [
                        $params['face_image'],
                        $params['face_scan']
                    ],
                ])

            ]);

            $data = $response->getBody();
            $read_json = json_decode($data, true);
            
            return ($read_json);
        }else{
            return ['status' => false, 'message' => 'Authorization data not yet set'];
        }
    }
}
