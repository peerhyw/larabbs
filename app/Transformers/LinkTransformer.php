<?php

namespace App\Transformers;

use App\Models\Link;
use League\Fractal\TransformerAbstract;

/**
 * link transformer
 */
class LinkTransformer extends TransformerAbstract
{
    public function transform(Link $link){
        return [
            'id' => $link->id,
            'title' => $link->title,
            'link' => $link->link,
        ];
    }

}