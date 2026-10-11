<?php

namespace App\Controllers;

class Weather extends BaseController
{
    // Activity 1: GET /weather  (saves a row on every call)
    public function index()
    {
        $url = 'https://api.open-meteo.com/v1/forecast'
            . '?latitude=8.2280&longitude=124.2452&current=temperature_2m';

        try {
            $client   = \Config\Services::curlrequest();
            $response = $client->get($url, ['timeout' => 10]);
            $result   = json_decode($response->getBody(), true);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(502)->setJSON([
                'error' => 'Weather source unreachable.',
            ]);
        }

        $temperature = $result['current']['temperature_2m'] ?? null;

        if ($temperature === null) {
            return $this->response->setStatusCode(502)->setJSON([
                'error' => 'No temperature received.',
            ]);
        }

        $model = new \App\Models\WeatherLogModel();
        $model->insert([
            'city'        => 'Iligan City',
            'temperature' => $temperature,
            'fetched_at'  => date('Y-m-d H:i:s'),
        ]);

        $latest = $model->orderBy('id', 'DESC')->findAll(10);

        return $this->response->setJSON([
            'city'         => 'Iligan City',
            'temperatureC' => (float) $temperature,
            'saved'        => count($latest),
            'logs'         => $latest,
        ]);
    }
}
