<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeRequest;
use App\Http\Resources\TypeResource;
use App\Models\Type;
use App\Services\ApiService;
use App\Services\TypeService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

    /**
    * @OA\Schema(
    *    schema="TypeRequest",
    *        @OA\Property(
    *            property="id",
    *            description="id",
    *            type="integer",
    *            nullable="false",
    *            example="1"
    *        ),
    *        @OA\Property(
    *            property="name",
    *            description="name",
    *            type="string",
    *            nullable="false",
    *            example="string"
    *        ),
    * )
    */

class TypeController extends Controller
{
    public function __construct(protected ApiService $apiService, protected TypeService $typeService)
    {}

    /**
     * @OA\Get(
     *     path="/api/types/",
     *     summary="List types",
     *     operationId="list_types",
     *     description="Returns a list of all document types registered",
     *     @OA\Response(
     *         response=200,
     *         description="Showing all document types",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *     )
     * )
    */
    public function index()
    {
        return $this->apiService->successResponse(new TypeResource($this->typeService->getAll()));
    }


    /**
    * @OA\Post(
    *     path="/api/types",
    *     summary="create a document types",
    *     description="create a document types",
    *     operationId="new_Type",
    *     @OA\RequestBody(
    *        @OA\JsonContent(ref="#/components/schemas/TypeRequest")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="document types created successfully",
    *         @OA\JsonContent(
    *             ref="#/components/schemas/TypeSchema"
    *         )
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="server error, try again"
    *     )
    * )
    */
    public function store(TypeRequest $request)
    {
        try{
            $model = $this->typeService->store($request->only(['name', 'active']));
            return $this->apiService->createdResponse(new TypeResource($model));
        } catch (\Exception $e){
            return $this->apiService->errorResponse();
        }
    }

    /**
    * @OA\GET(
    *     path="/api/types/{id}",
    *     summary="Get document type",
    *     description="Returns information about document type by id",
    *     operationId="get_Type_by_id",
    *     @OA\Parameter(
    *         name="id",
    *         description="id",
    *         in="path",
    *         required=true,
    *         example="1"
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="document types retrieved successfully",
    *         @OA\JsonContent(
    *             ref="#/components/schemas/TypeSchema"
    *         )
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="server error, try again",
    *     )
    * )
    */
    public function show(string $id)
    {
        try{
            return $this->apiService->successResponse(new TypeResource($this->typeService->getById($id)));
        } catch(ModelNotFoundException $e){
            return $this->apiService->errorRecordNotFoundResponse();
        } catch (\Exception $e){
            return $this->apiService->errorResponse();
        }
    }


    /**
    * @OA\Put(
    *     path="/api/types/{id}",
    *     summary="update a document types",
    *     description="update a document types",
    *     operationId="update_Type",
    *     @OA\RequestBody(
    *        @OA\JsonContent(ref="#/components/schemas/TypeRequest")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="document types updated successfully",
    *         @OA\JsonContent(
    *             ref="#/components/schemas/TypeSchema"
    *         )
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="server error, try again"
    *     )
    * )
    */
    public function update(TypeRequest $request, string $id)
    {
        try{
            return $this->apiService->successResponse(
                new TypeResource(
                    $this->typeService->update($id, $request->only(['name', 'active']))
                )
            );
        } catch(ModelNotFoundException $e){
            return $this->apiService->errorRecordNotFoundResponse();
        } catch (\Exception $e){
            return $this->apiService->errorResponse();
        }
    }

    /**
    * @OA\Delete(
    *     path="/api/types/{id}",
    *     summary="delete a document types",
    *     description="delete a document types",
    *     operationId="delete_Type",
    *     @OA\RequestBody(
    *        @OA\JsonContent(ref="#/components/schemas/TypeRequest")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="document type deleted successfully",
    *         @OA\JsonContent(
    *             ref="#/components/schemas/TypeSchema"
    *         )
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="server error, try again"
    *     )
    * )
    */
    public function destroy(string $id)
    {
        try{
            return $this->apiService->successResponse(null, $this->typeService->destroy($id));
        } catch(ModelNotFoundException $e){
            return $this->apiService->errorRecordNotFoundResponse();
        } catch (\Exception $e){
            return $this->apiService->errorResponse();
        }
    }
}
