<?php

namespace App\Mine;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\{Request, JsonResponse};

use Illuminate\Support\Facades\Schema;
use Exception;

class MyController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $collection= MyResourceCollection::class;
    protected $validation= [];

    protected function validatePagination(Request $request):void{
        $pagination= $request->pagination;
        $page= $request->page;
        ($pagination<1) && $request->query->set('pagination', 15);
        ($page<1) && $request->query->set('page', 1);
    }

    public function count(Request $request){
        return new JsonResponse([
            'data'=> $this->model::count(),
            'message'=> 'A simple count operation, count the total data inside the table.'
        ], 200);
    }

    public function list(Request $request){
        $this->validatePagination($request);
        return new $this->collection($this->model::paginate($request->get('pagination')));
    }

    public function create(Request $request){
        $this->validate($request, $this->validation);
        try{
            return new JsonResponse([
                'message'=> 'New '.$this->model::modelName().' data is created.',
                'data'=> new $this->resource($this->model::create($request->post()))
            ], 201);

        }catch(Exception $e){
            return new JsonResponse([
                'message'=> $e->errorInfo[2]
            ], 400);
        }
    }

    public function find(Request $request, string $id){
        try{
            return new $this->resource($this->model::findOrFail($id));
        }catch(Exception $e){
            return new JsonResponse([
                "message"=> $this->model::modelName()." data with id= ".$id." is not found."
            ], 404);
        }
    }

    public function replace(Request $request, string $id){
        try{
            $instance= $this->model::findOrFail($id);
        }catch(Exception $e){
            return new JsonResponse([
                'message'=> $this->model::modelName()." data with id= ".$id." is not found."
            ], 404);
        }

        $properties= array_values(array_diff(Schema::getColumnListing($instance->getTable()), ['id']));

        try{
            foreach($properties as $property){
                $instance[$property]= $request->post($property, null);
            }
        }catch(Exception $e){
            return new JsonResponse([
                'message'=> $e->errorInfo[2]
            ], 400);
        }
        $instance->save();
        return new $this->resource($instance); 
    }

    public function update(Request $request, string $id){
        try{
            $instance= $this->model::findOrFail($id);
        }catch(Exception $e){
            return new JsonResponse([
                'message'=> $this->model::modelName()." data with id= ".$id." is not found."
            ], 404);
        }
        try{
            $instance->update($request->post());
            return new $this->resource($instance); 
        }catch(Exception $e){
            return new JsonResponse([
                'message'=> $e->errorInfo[2]
            ], 400);
        }
    }

    public function delete(Request $request, string $id){
        try{
            $instance= $this->model::findOrFail($id);
        }catch(Exception $e){
            return new JsonResponse([
                'message'=> $this->model::modelName()." data with id= ".$id." is not found."
            ], 404);
        }
        $instance->delete();
        return new JsonResponse(null, 204);
    }
}