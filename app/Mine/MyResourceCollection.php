<?php

namespace App\Mine;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MyResourceCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }    

    public function paginationInformation($request, $paginated, $default)
    {
        return [
            'message'=> 'collection',
            'meta'=>[
                'pagination'=> $default['meta']['per_page'],
                'page'=> $default['meta']['current_page'],
                'total_data'=> $default['meta']['total']
            ]
        ];
    }
}
