<?php

namespace common\models;

use Yii;
use yii\db\Exception;

class GoogleMaps {
    private $baseUrl;
    private $apiKey;

    public function __construct()
    {
        $this->baseUrl = 'https://maps.googleapis.com/maps/api/';
        $this->apiKey = Yii::$app->params['googleMapsApiKey'];
    }

    public function getDistanceMatrix($from, $where) {
        $url = $this->baseUrl . 'distancematrix/json?units=imperial&origins=' . $from['latlng'] . '&destinations=' . $where['latlng'] . '&key=' . $this->apiKey;
        $response = file_get_contents($url);

        return $this->processDurationResponse(json_decode($response, true));
    }

    public function getLatLng($addressString) {
        if (($data = AddressLatlng::tryGetAddressData($addressString))) {
            return ['success' => true, 'latlng' => $data->latlng];
        }

        $url = $this->baseUrl . 'geocode/json?address=' . urlencode($addressString) . '&key=' . $this->apiKey;
        $response = file_get_contents($url);

        $result = $this->processLatLngResponse(json_decode($response, true));

        if ($result['success']) {
            $addressLatlng = new AddressLatlng();
            $addressLatlng->address = trim($addressString);
            $addressLatlng->latlng = $result['latlng'];

            $addressLatlng->save();
        }

        return $result;
    }

    private function processLatLngResponse($response) {
        if (!isset($response) ||
            !isset($response['results']) ||
            !isset($response['results'][0]['address_components']) ||
            !isset($response['results'][0]['geometry']) ||
            !isset($response['results'][0]['geometry']['location'])
        ) {
            return ['success' => false, 'message' => 'Incorrect address', 'response' => $response];
        }

        if (!$this->checkIsAddressAllowed($response['results'][0]['address_components'])) {
            return ['success' => false, 'message' => 'Address not supported', 'response' => $response];
        }

        return ['success' => true, 'latlng' => $response['results'][0]['geometry']['location']['lat'] . ',' . $response['results'][0]['geometry']['location']['lng']];
    }

    private function checkIsAddressAllowed($address_components) {
        if(is_array($address_components)) {
            foreach ($address_components as $component) {
                if (in_array('political', $component['types'])) {
                    if (($allowed = AllowedStates::findOne(['state_name' => $component['long_name'], 'is_active' => 1]))) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    private function processDurationResponse($response)
    {
        if (!isset($response['rows']) || !isset($response['rows'][0]['elements']) || !isset($response['rows'][0]['elements'][0]['duration'])) {
            return ['success' => false, 'message' => 'No data about duration'];
        }

        return ['success' => true, 'duration' => $response['rows'][0]['elements'][0]['duration']['text'], 'distance' => $response['rows'][0]['elements'][0]['distance']['value']];
    }
}