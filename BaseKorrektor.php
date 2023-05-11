<?php

namespace uzdevid\korrektor;

use yii\base\Component;
use yii\base\Exception;

/**
 * @property string $token
 * @property string $url
 */
class BaseKorrektor extends Component {
    public string $endpointUrl = 'https://api.korrektor.uz';

    public string $method;

    private string $_token = 'D2~0$oau@Zp{Wy06B!Ye$DmUT(P1Q{$t';

    public function getToken() {
        return $this->_token;
    }

    public function setToken(string $token) {
        $this->_token = $token;
    }

    protected function getUrl() {
        return $this->endpointUrl . $this->method;
    }

    protected function curlExecute(string $url, array $postFields) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postFields, JSON_UNESCAPED_UNICODE),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->token}",
                'Content-Type: application/json'
            ],
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);
    }
}