<?php

class Geocode extends Controller
{
    public function reverse()
    {
        header('Access-Control-Allow-Origin: https://foodlink-react.onrender.com');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }
        // Kunin ang lat at lng mula sa URL query parameters
        $lat = $this->request->getGet('lat');
        $lng = $this->request->getGet('lng');

        if (empty($lat) || empty($lng)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Missing lat/lng parameters'
            ]);
        }

        // Option 1: Google Maps (Kung may API key ka sa .env)
        $googleKey = getenv('GOOGLE_MAPS_KEY');
        if ($googleKey) {
            $googleUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&key={$googleKey}";
            $googleResponse = @file_get_contents($googleUrl);
            
            if ($googleResponse) {
                $googleData = json_decode($googleResponse, true);
                if (isset($googleData['results'][0]['formatted_address'])) {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'address' => $googleData['results'][0]['formatted_address']
                    ]);
                }
            }
        }

        // Option 2: Nominatim (Free fallback)
        $options = [
            'http' => [
                'header' => "User-Agent: FoodLink/1.0 (your@email.com)\r\n"
            ]
        ];
        $context = stream_context_create($options);
        
        $nominatimUrl = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lng}&zoom=18&addressdetails=1";
        $nominatimResponse = @file_get_contents($nominatimUrl, false, $context);

        if ($nominatimResponse) {
            $data = json_decode($nominatimResponse, true);
            $a = $data['address'] ?? [];

            // I-format nang maikli: "Sapul, Calapan City"
            $parts = [];
            if (!empty($a['suburb'])) $parts[] = $a['suburb'];
            elseif (!empty($a['village'])) $parts[] = $a['village'];
            elseif (!empty($a['neighbourhood'])) $parts[] = $a['neighbourhood'];
            
            if (!empty($a['city'])) $parts[] = $a['city'];
            elseif (!empty($a['town'])) $parts[] = $a['town'];
            elseif (!empty($a['municipality'])) $parts[] = $a['municipality'];

            $shortAddress = implode(', ', $parts);
            $finalAddress = $shortAddress ?: ($data['display_name'] ?? "GPS: {$lat}, {$lng}");

            return $this->response->setJSON([
                'status' => 'success',
                'address' => $finalAddress
            ]);
        }

        // Fallback kung nabigo lahat
        return $this->response->setJSON([
            'status' => 'success',
            'address' => "GPS: {$lat}, {$lng}"
        ]);
    }
}