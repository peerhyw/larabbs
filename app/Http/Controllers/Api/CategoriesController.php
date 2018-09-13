<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Transformers\CategoryTransformer;

class CategoriesController extends Controller
{
    public function index(){
        //注意此处collection 与 item 的区别  item单个条目 collection多个条目
        return $this->response->collection(Category::all(),new CategoryTransformer());
    }
}
