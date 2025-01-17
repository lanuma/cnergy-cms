<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::whereNull("parent_id");

        if ($request->get('inputCategory')) {
            $categories->where('category', 'like', '%' . $request->inputCategory . '%');
        }

        if ($request->get('inputSlug')) {
            $categories->where('slug', 'like', '%' . $request->inputSlug . '%');
        }

        if ($request->get('status')) {
            $status = $request->status;
            if ($status == 2) {
                $categories->where('is_active', "0");
            } else {
                $categories->where('is_active', "1");
            }
        }

        $category = Category::whereNull('parent_id')->with(["children"])->get();
        $data = $this->convertDataToResponse($category);
        return response()->json($data);
       
    }

    private function convertDataToResponse($dataRaw){
        
        return $dataRaw->transform(function ($data, $key) {
            return [
                "id" => $data->id,
                "parent" => $data->parent_id,
                "name" => $data->category,
                "common" => strtolower($data->category),
                "url" => $data->slug,
                "type" => $data->types,
                "meta_name" => "",
                "meta_description" => "",
                "children" => $this->convertDataToResponse($data->children),
            ];
        });
    }

}
