<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Models\ColumnDocument;
use App\Models\Document;
use App\Services\DocumentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF;

    /**
    * @OA\Schema(
    *    schema="DocumentRequest",
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
class DocumentController extends Controller
{
    public function __construct(protected ApiService $apiService, protected DocumentService $documentService)
    {}

    /**
     * @OA\Get(
     *     path="/api/documents/",
     *     summary="List documents",
     *     operationId="list_documents",
     *     description="Returns a list of all documents registered",
     *     @OA\Response(
     *         response=200,
     *         description="Showing all Documents",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *     )
     * )
    */
    public function index()
    {
        return $this->apiService->successResponse(new DocumentResource($this->documentService->getAll()));
    }


    /**
    * @OA\Post(
    *     path="/api/documents",
    *     summary="create a document",
    *     description="create a document",
    *     operationId="new_document",
    *     @OA\RequestBody(
    *        @OA\JsonContent(ref="#/components/schemas/DocumentRequest")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="document created successfully",
    *         @OA\JsonContent(
    *             ref="#/components/schemas/DocumentSchema"
    *         )
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="server error, try again"
    *     )
    * )
    */
    public function store(DocumentRequest $request)
    {
        try{
            $model = $this->documentService->store($request->only(['name', 'active', 'type_id', 'content']));
            return $this->apiService->createdResponse(new DocumentResource($model));
        } catch (\Exception $e){
            return $this->apiService->errorResponse();
        }
    }

    /**
    * @OA\GET(
    *     path="/api/documents/{id}",
    *     summary="Get document",
    *     description="Returns information about document by id",
    *     operationId="get_document_by_id",
    *     @OA\Parameter(
    *         name="id",
    *         description="id",
    *         in="path",
    *         required=true,
    *         example="1"
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="document retrieved successfully",
    *         @OA\JsonContent(
    *             ref="#/components/schemas/DocumentSchema"
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
            return $this->apiService->successResponse(new DocumentResource($this->documentService->getById($id)));
        } catch(ModelNotFoundException $e){
            return $this->apiService->errorRecordNotFoundResponse();
        } catch (\Exception $e){
            return $this->apiService->errorResponse();
        }
    }


    /**
    * @OA\Put(
    *     path="/api/documents/{id}",
    *     summary="update a document",
    *     description="update a document",
    *     operationId="update_document",
    *     @OA\RequestBody(
    *        @OA\JsonContent(ref="#/components/schemas/DocumentRequest")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="document updated successfully",
    *         @OA\JsonContent(
    *             ref="#/components/schemas/DocumentSchema"
    *         )
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="server error, try again"
    *     )
    * )
    */
    public function update(DocumentRequest $request, string $id)
    {
        try{
            return $this->apiService->successResponse(
                new DocumentResource(
                    $this->documentService->update($id, $request->only(['name', 'active', 'type_id', 'content']))
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
    *     path="/api/documents/{id}",
    *     summary="delete a document",
    *     description="delete a document",
    *     operationId="delete_document",
    *     @OA\RequestBody(
    *        @OA\JsonContent(ref="#/components/schemas/DocumentRequest")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="document deleted successfully",
    *         @OA\JsonContent(
    *             ref="#/components/schemas/DocumentSchema"
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
            return $this->apiService->successResponse(null, $this->documentService->destroy($id));
        } catch(ModelNotFoundException $e){
            return $this->apiService->errorRecordNotFoundResponse();
        } catch (\Exception $e){
            return $this->apiService->errorResponse();
        }
    }

    /**
    * @OA\GET(
    *     path="/api/documents/{id}/download",
    *     summary="Download document",
    *     description="Returns pdf file for a document",
    *     operationId="download_document_by_id",
    *     @OA\Parameter(
    *         name="id",
    *         description="id",
    *         in="path",
    *         required=true,
    *         example="1"
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="download successfully",
    *         @OA\JsonContent(
    *             ref="#/components/schemas/DocumentSchema"
    *         )
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="server error, try again",
    *     )
    * )
    */
    public function download($id)
    {
        try{
            $data = [
                'document' => Document::findOrFail($id)
            ];

            $pdf = PDF::loadView('pdf-document',$data);
            return $pdf->download('pdf-document.pdf');
        } catch(ModelNotFoundException $e){
            return response()->json(['errors' => 'Document not found'], 400);
        } catch (\Exception $e){
            log::error('Update Document Exception - '. $e);
            return response()->json(['errors' => 'server error, try again'], 500);
        }

    }
}
