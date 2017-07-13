<?php

namespace alexjose;

use GuzzleHttp\Client;

class ZoomApi{

    private $apiKey;
	private $apiSecret;
	private $apiUrl;
    private $apiClient;

    public function __construct($apiKey, $apiSecret, $apiUrl = 'https://api.zoom.us/v1/')
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->apiUrl = $apiUrl;

        $this->apiClient = new Client([
            // Base URI is used with relative requests
            'base_uri' => $this->apiUrl,
        ]);
    }

    public function send($uri, $data = [])
    {
        $data['api_key'] = $this->apiKey;
        $data['api_secret'] = $this->apiSecret;

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
    * @param integer $pageSize The amount of records returns within a single API call. Max of 300 users.
    * @param integer $pageNumber Current page number of returned records. Default to 1.
    * @return array
    */
    public function listUsers($pageSize = 30, $pageNumber = 1)
    {
        $data['page_size'] = $pageSize;
        $data['page_number'] = $pageNumber;

        return $this->send('user/list', $data);
    }

    /**
    * 
    * Create A Meeting
    *
    * @param string $hostId Meeting host user ID. Can be any user under this account
    * @param integer $type Meeting type
    * @param string $topic Meeting topic. Max of 300 characters
    * @param array $data Additional Data
    * @return array
    */
    public function createMeeting($hostId, $type, $topic, $data = [])
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