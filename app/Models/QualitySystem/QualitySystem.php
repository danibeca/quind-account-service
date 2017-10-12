<?php

namespace App\Models\QualitySystem;

use function foo\func;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class QualitySystem extends Model
{
    protected $fillable = ['name'];


    public function qualitySystems()
    {
        return $this->belongsToMany('App\Models\Account\Component')->withPivot(['url', 'type']);
    }


    public function resources()
    {
        $client = new Client();
        $url = $this->pivot->url . '/api/resources';
        try
        {
            return collect(json_decode($client->get($url)->getBody()->getContents()))->map(function ($item, $key) {
                return ['key' => $item->key, 'name' => $item->name];
            });

        } catch (RequestException $e)
        {
            return [];
        }
    }
}
