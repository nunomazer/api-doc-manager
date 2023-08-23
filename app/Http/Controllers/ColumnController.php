<?php

namespace App\Http\Controllers;

use App\Http\Requests\ColumnRequest;
use App\Http\Resources\ColumnResource;
use App\Models\Column;
use App\Services\ApiService;
use App\Services\ColumnService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

    /**
    * @OA\Schema(
    *    schema="ColumnRequest",
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
    *        @OA\Property(
    *            property="type_id",
    *            description="Type id",
    *            type="integer",
    *            nullable="false",
    *            example="1"
    *        ),
    * )
    */

class ColumnController extends Controller
{
    public function __construct(protected ApiService $apiService, protected ColumnService $columnService)
    {}

    /**
     * @OA\Get(
     *     path="/api/columns/",
     *     summary="List columns",
     *     operationId="list_columns",
     *     description="Returns a list of all colunms registered",
     *     @OA\Response(
     *         response=200,
     *         description="Showing all Column types",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *     )
     * )
    */
    public function index()
    {
        return $this->apiService->successResponse(new ColumnResource($this->columnService->getAll()));
    }


    /**
    * @OA\Post(
    *     path="/api/columns",
    *     summary="create a column",
    *     description="create a column",
    *     operationId="new_column",
    *     @OA\RequestBody(
    *        @OA\JsonContent(ref="#/components/schemas/ColumnRequest")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Column type created successfully",
    *         @OA\JsonContent(
    *             ref="#/components/schemas/ColumnSchema"
    *         )
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="server error, try again"
    *     )
    * )
    */
    public function store(ColumnRequest $request)
    {
        try{
            $model = $this->columnService->store($request->only(['name', 'active', 'type_id']));
            return $this->apiService->createdResponse(new ColumnResource($model));
        } catch (\Exception $e){
            return $this->apiService->errorResponse();
        }
    }

    /**
    * @OA\GET(
    *     path="/api/columns/{id}",
    *     summary="Get columns",
    *     description="Returns information about column by id",
    *     operationId="get_column_by_id",
    *     @OA\Parameter(
    *         name="id",
    *         description="id",
    *         in="path",
    *         required=true,
    *         example="1"
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Column type retrieved successfully",
    *         @OA\JsonContent(
    *             ref="#/components/schemas/ColumnSchema"
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
            return $this->apiService->successResponse(new ColumnResource($this->columnService->getById($id)));
        } catch(ModelNotFoundException $e){
            return $this->apiService->errorRecordNotFoundResponse();
        } catch (\Exception $e){
            return $this->apiService->errorResponse();
        }
    }


    /**
    * @OA\Put(
    *     path="/api/columns/{id}",
    *     summary="update a column",
    *     description="update a column",
    *     operationId="update_column",
    *     @OA\RequestBody(
    *        @OA\JsonContent(ref="#/components/schemas/ColumnRequest")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Column type updated successfully",
    *         @OA\JsonContent(
    *             ref="#/components/schemas/ColumnSchema"
    *         )
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="server error, try again"
    *     )
    * )
    */
    public function update(ColumnRequest $request, string $id)
    {
        try{
            return $this->apiService->successResponse(
                new ColumnResource(
                    $this->columnService->update($id, $request->only(['name', 'active', 'type_id']))
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
    *     path="/api/columns/{id}",
    *     summary="delete a column",
    *     description="delete a column",
    *     operationId="delete_column",
    *     @OA\RequestBody(
    *        @OA\JsonContent(ref="#/components/schemas/ColumnRequest")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Column type deleted successfully",
    *         @OA\JsonContent(
    *             ref="#/components/schemas/ColumnSchema"
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
            return $this->apiService->successResponse(null, $this->columnService->destroy($id));
        } catch(ModelNotFoundException $e){
            return $this->apiService->errorRecordNotFoundResponse();
        } catch (\Exception $e){
            return $this->apiService->errorResponse();
        }
    }
}
