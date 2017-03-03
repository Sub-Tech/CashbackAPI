<?php

namespace CashbackApi\Media;


use CashbackApi\BaseApi;

class Media extends BaseApi
{

    public function getMediaList($path, $type = null)
    {
        $data = new \stdClass();
        $data->path = $path;
        $data->type = $type;
        return $this->doRequest('media/get', $data);
    }

    public function getImages($path)
    {
        return $this->getMediaList($path, 'image');
    }

    public function getVideos($path)
    {
        return $this->getMediaList($path, 'video');
    }

}