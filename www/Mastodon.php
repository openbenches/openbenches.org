<?php

    class MastodonAPI
    {
        private $token;
        private $instance_url;

        public function __construct($token, $instance_url)
        {
            $this->token = $token;
            $this->instance_url = $instance_url;
        }

        public function postStatus($status)
        {
            return $this->callAPI('/api/v1/statuses', 'POST', $status);
        }

        public function uploadMedia($media)
        {
            return $this->callAPI('/api/v1/media', 'POST', $media);
        }

        public function callAPI($endpoint, $method, $data)
        {
            $headers = [
                'Authorization: Bearer '.$this->token,
                'Content-Type: multipart/form-data',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->instance_url.$endpoint);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $reply = curl_exec($ch);

            if (!$reply) {
                return json_encode(['ok'=>false, 'curl_error_code' => curl_errno($ch_status), 'curl_error' => curl_error(ch_status)]);
            }
            curl_close($ch);

            return json_decode($reply, true);
        }
    }
