<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * Used request parameters `hide_parent` and `hide_children` for reducing response size
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'depth'        => $this->depth,
            'parent'       => !$request->has('hide_parent') ? $this->parent : null,
            'children'     => !$request->has('hide_children') ? $this->children : null,
            'has_children' => $this->children->count() ? true : false
        ];
    }
}
