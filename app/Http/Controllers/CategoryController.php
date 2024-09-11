<?php

namespace App\Http\Controllers;

use App\Models\CategoryImage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{

    public $model = CategoryImage::class;
    public $s = "imagen";
    public $sp = "imagenes";
    public $ss = "imagen/es";
    public $v = "a"; 
    public $pr = "la"; 
    public $prp = "las";

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'cod_category' => 'required',
    //         // 'img' => 'required',
    //     ]);

    //     try {
    //         if($request->img)
    //             $response_save_image = $this->save_image_public_folder($request->img, "categories/images/");

    //         $category_image = new $this->model();
    //         $category_image->sector = 3;
    //         $category_image->cod_category = $request->cod_category;
    //         $category_image->img = $response_save_image['path'] ?? null;
    //         $category_image->color = $request->color;
    //         $category_image->save();

    //     } catch (Exception $error) {
    //         Log::debug("Error al guardar imagen: " . $error->getMessage() . ' line: ' . $error->getLine());
    //         return response(["message" => "Error al guardar imagen", "error" => $error->getMessage()], 500);
    //     }
       
    //     return response()->json(['message' => 'Imagen guardada exitosamente.'], 200);
    // }

    public function store(Request $request)
    {
        $request->validate([
            'cod_category' => 'required',
            // 'img' => 'required',
        ]);

        try {
                $category_image = $this->model::where('cod_category', $request->cod_category)->first();
                
                if(!isset($category_image))
                    $category_image = new $this->model();
                
                $category_image->sector = 3;
                $category_image->cod_category = $request->cod_category;

                if($request->img){
                    $response_save_image = $this->save_image_public_folder($request->img, "categories/images/");
                    $category_image->img = $response_save_image['path'] ?? null;
                }

                if($request->color)
                    $category_image->color = $request->color;
                
                $category_image->save();
             
        } catch (Exception $error) {
            Log::debug("Error al guardar imagen: " . $error->getMessage() . ' line: ' . $error->getLine());
            return response(["message" => "Error al guardar imagen", "error" => $error->getMessage()], 500);
        }
       
        return response()->json(['message' => 'imagen/color de categoria guardado exitosamente.'], 200);
    }

    public function save_image_public_folder($file, $path_to_save)
    {
        try {
            $fileName = Str::random(5) . time() . '.' . $file->extension();
            $file->move(public_path($path_to_save), $fileName);
            $path = "/" . $path_to_save . $fileName;
            return ["status" => 200, "path" => $path];
        } catch (Exception $error) {
            return ["status" => 500, "message" => $error->getMessage()];
        }
    }

    public function category_images($cod_category)
    {
        $category_images = CategoryImage::where('cod_category', $cod_category)->get();

        return response()->json(['category_images' => $category_images], 200);
    }

    public function categories()
    {
        $categories = $this->model::all();

        return response()->json(['categories' => $categories], 200);
    }

    public function delete_category_image($image_id)
    {
        $category_image = $this->model::find($image_id);
        
        if(!$category_image)
            return response()->json(['message' => 'ID image invalido.'], 400);
        
        $category_image->delete();
    
        return response()->json(['message' => 'Imagen eliminada con exito.'], 200);
    }
}
