<?php

namespace Zoom;

use GuzzleHttp\Client;

class ZoomApi{

    private $api_key;
	private $api_secret;
	private $api_url;
    private $apiClient;

    public function __construct($api_key, $api_secret, $api_url = 'https://api.zoom.us/v1/')
    {
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
        $this->api_url = $api_url;

        $this->apiClient = new Client([
            // Base URI is used with relative requests
            'base_uri' => $this->api_url,
        ]);
    }

    public function send($uri, $data = [])
    {
        $data['api_key'] = $this->api_key;
        $data['api_secret'] = $this->api_secret;

        try{
            $response = $this->apiClient->post($uri,  [
                'form_params' => $data
            ]);

            // $body = $response->getBody()->getContents();
            $result = json_decode($response->getBody(), true);
        }catch(GuzzleHttp\Exception\ClientException $ex){
            throw $ex;
        }

        return $result;
    }

    /**
    * 
    * List Users
    *
    * @param integer $page_size The amount of records returns within a single API call. Max of 300 users.
    * @param integer $page_number Current page number of returned records. Default to 1.
    * @return array
    */
    public function listUsers($page_size = 30, $page_number = 1)
    {
        $data['page_size'] = $page_size;
        $data['page_number'] = $page_number;

        return $this->send('user/list', $data);
    }

    /**
    * 
    * Create A Meeting
    *
    * @param string $host_id Meeting host user ID. Can be any user under this account
    * @param integer $type Meeting type
    * @param string $topic Meeting topic. Max of 300 characters
    * @param array $data Additional Data
    * @return array
    */
    public function createMeeting($host_id, $type, $topic, $data = [])
    {

        $validTypes = [1, 2, 3, 8];

        if(!in_array($type, $validTypes)){
            throw new \InvalidArgumentException('Invalid `type` passed. Possible values for `type` are ' . implode(", ", $validTypes));
        }

        $optionalFields = [
            'start_time',
            'duration',
            'timezone',
            'password',
            'recurrence',
            'option_registration',
            'registration_type',
            'option_jbh',
            'option_start_type',
            'option_host_video',
            'option_participants_video',
            'option_cn_meeting',
            'option_in_meeting',
            'option_audio',
            'option_enforce_login',
            'option_enforce_login_domains',
            'option_alternative_hosts',
            'option_alternative_host_ids',
            'option_use_pmi',
            'option_auto_record_type',
        ];

        $invalidDataKeys = array_diff_key($data, $optionalFields);

        if(!empty($invalidDataKeys)){
            throw new \InvalidArgumentException('Invalid data passed. `' . implode(", ", $invalidDataKeys) . '` are not allowed.');
        }

        $data['host_id'] = $host_id;
        $data['type'] = $type;
        $data['topic'] = $topic;

        return $this->send('meeting/create', $data);
    }

}