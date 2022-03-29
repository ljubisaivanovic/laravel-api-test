<?php

namespace App\Http\Controllers\Api;

class Client
{

    protected $extraHeaders = [];

    protected $headers;

    protected $url = 'https://symfony-skeleton.q-tests.com/api/v2/';

    protected $email;

    protected $password;

    protected $apiKey;

    protected $apiCallUrl;

    protected $token;

    /**
     * @throws \Exception
     */
    public function init()
    {
        if ($this->validateConfig()) {
            return $this->getToken();
        }
        return false;
    }

    /**
     * Accepted: admin or customer.
     *
     * @param string $integration
     * @return bool|mixed
     * @throws \Exception
     */
    protected function getToken()
    {
        $data = [
            'email' => $this->getEmail(),
            'password' => $this->getPassword()
        ];

        if ($token = $this->call('token', $data, "POST")) {
            return $token;
        }
    }

    /**
     * @return array
     */
    protected function getDefaultHeaders()
    {
        $headers = [];
        if (session()->get('token_key')) {
            $headers[] = 'Authorization: Bearer ' . session()->get('token_key');
            $headers['realtime-stock'] = 'Disable-RealtimeStock: true';

        } elseif ($this->token) {
            $headers[] = 'Authorization: Bearer ' . $this->token;
            $headers['realtime-stock'] = 'Disable-RealtimeStock: true';

        }

        $headers = array_merge($headers, $this->extraHeaders);

        return $headers;
    }

    /**
     * @param $url
     * @param array $dataArray
     * @param string $postType
     * @param string $storeCode
     * @param array $extraHeaders
     * @return bool|mixed
     * @throws \Exception
     */
    public function call($url, $dataArray = [], $postType = "GET", $extraHeaders = [])
    {
        $this->extraHeaders = $extraHeaders;
        $handle = curl_init();
        $this->apiCallUrl = trim($this->url, '/') . '/' . ltrim($url, '/');
        $this->headers = $this->getDefaultHeaders();

        curl_setopt($handle, CURLOPT_URL, $this->apiCallUrl);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_ENCODING, '');
        curl_setopt($handle, CURLOPT_MAXREDIRS, 10);
        curl_setopt($handle, CURLOPT_TIMEOUT, 0);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);

        switch ($postType) {
            case 'GET':
                $this->buildGetCall($handle, $dataArray);
                break;
            case 'POST':
                $this->buildPostCall($handle, $dataArray);
                break;
            case 'PUT':
                $this->buildPutCall($handle, $dataArray);
                break;
            case 'DELETE':
                $this->buildDeleteCall($handle, $dataArray);
                break;
        }
        curl_setopt($handle, CURLOPT_HTTPHEADER, $this->headers);

        $this->setDefaultOptions($handle);
        $response = curl_exec($handle);

        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        curl_close($handle);

        $response = [
            'response' => $response,
            'code' => $code
        ];

        $code = $response['code'];
        $response = $response['response'];
        if ($code == '200') {
            return json_decode($response, true);

        } elseif ($code == '202') {
            return true;
        } elseif ($code == '204') {
            return true;
        } elseif ((int)$code >= 300) {
            if ($decodedResponse = json_decode($response)) {
                if (isset($decodedResponse->message)) {
                    $msg = $decodedResponse->message;
                } else {
                    $msg = $decodedResponse->detail;
                }
                $exception = $code . " - Error making request to server: " . $msg;

                throw new \Exception($exception, $code);
            }
            throw new \Exception($code . " - Error making request to server:\n" . $response, $code);
        }
        throw new \Exception($code . " - Error making request to server no valid response:\n" . $response, $code);
    }


    /**
     * @return bool
     * @throws \Exception
     */
    protected function validateConfig()
    {
        $missingConfig = [];
        if (!$this->getEmail()) {
            $missingConfig[] = 'email';
        } elseif (!$this->getPassword()) {
            $missingConfig[] = 'password';
        } elseif (!$this->url) {
            $missingConfig[] = 'url';
        }

        if (!empty($missingConfig)) {
            $missingConfigString = implode(', ', $missingConfig);
            throw new \Exception("One or more config values are missing: {$missingConfigString}");
        }

        return true;
    }


    /**
     * @param $handle
     */
    protected function setDefaultOptions($handle)
    {
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
    }

    /**
     * @param $handle
     * @param $dataArray
     */
    protected function buildPostCall($handle, $dataArray)
    {
        $dataJson = json_encode($dataArray);
        switch (json_last_error()) {
            case JSON_ERROR_UTF8:
                $dataArray = $this->utf8ize($dataArray);
                $dataJson = json_encode($dataArray);
                break;
        }

        curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($handle, CURLOPT_POSTFIELDS, $dataJson);

        $this->headers[] = 'Content-Type: application/json';
        $this->headers[] = 'Content-Length: ' . strlen($dataJson);
    }

    /**
     * @param $handle
     * @param $dataArray
     */
    protected function buildPutCall($handle, $dataArray)
    {
        $this->buildPostCall($handle, $dataArray);

        curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
    }

    /**
     * @param $handle
     * @param $dataArray
     */
    protected function buildDeleteCall($handle, $dataArray)
    {
        curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }

    /**
     * @param $handle
     * @param $dataArray
     */
    protected function buildGetCall($handle, $dataArray)
    {
        if (!empty($dataArray)) {
            $urlParameters = http_build_query($dataArray);
            $this->apiCallUrl .= '?' . $urlParameters;
        }
    }

    /**
     * @param $mixed
     * @return array|string
     */
    protected function utf8ize($mixed)
    {
        if (is_array($mixed) || is_object($mixed)) {
            foreach ($mixed as $key => $value) {
                if (is_array($mixed)) {
                    $mixed[$key] = $this->utf8ize($value);
                } else {
                    $mixed->$key = $this->utf8ize($value);
                }
            }
        } elseif (is_string($mixed)) {
            return utf8_encode($mixed);
        }

        return $mixed;
    }


    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getPassword()
    {
        return $this->password;
    }

    public function setToken(string $token)
    {
        $this->token = $token;

        return $this;
    }

    public function checkTokenExpirationTime($token)
    {
        $start_date = date_create(session()->get('token_time'));
        $end_date = date_create(date('Y-m-d'));
        $interval = date_diff($start_date, $end_date);

        $days = $interval->format('%d');

        if ($days >= 7) {
            try {
                $clientResponse = $this->call('token/refresh/' . $token);
                $userData = collect([
                    'first_name' => $clientResponse['user']['first_name'],
                    'last_name' => $clientResponse['user']['last_name'],
                    'gender' => $clientResponse['user']['gender']
                ]);
                session()->start();
                session()->put('userData', $userData);
                session()->put('token_key', $clientResponse['token_key']);
                session()->put('refresh_token_key', $clientResponse['refresh_token_key']);
                session()->put('token_time', date('Y-m-d'));

                return true;
            } catch (\Exception $e) {
              return false;
            }

        }

        return true;
    }
}
