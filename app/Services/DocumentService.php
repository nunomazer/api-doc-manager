<?php

namespace App\Services;

use App\Models\Document;
use App\Services\Interfaces\DocumentInterface;
use Barryvdh\DomPDF\PDF;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class DocumentService implements DocumentInterface
{

    public function store(array $data): Document
    {
        try{
            $model = Document::create($data);

            if ( ! empty($data['content']) && is_array($data['content'])){
                $documentContent = [];
                foreach($data['content'] as $content){
                    $documentContent[$content['column_id']] = ['content' => $content['text']];
                }
                $model->columns()->sync($documentContent);
            }

            return $model;
        } catch (\Exception $e){
            log::error('Store Document Exception');
            log::error($e);
            throw $e;
        }
    }

    public function update(int $id, array $data): Document
    {
        $model = Document::findOrFail($id);

        try{
            return $model->fill($$data)->update();

            if ( ! empty($data['content']) && is_array($data['content'])){
                $documentContent = [];
                foreach($data['content'] as $content){
                    $documentContent[$content['column_id']] = ['content' => $content['text']];
                }
                $model->columns()->sync($documentContent);
            }
        } catch (\Exception $e){
            log::error('Update Document Exception');
            log::error($e);
            throw $e;
        }

        return $model;
    }

    public function destroy(int $id): bool
    {
        $model = Document::findOrFail($id);

        try{
            $model->columns->detach();
            return $model->delete();
        } catch (\Exception $e){
            log::error('Update Document Exception');
            log::error($e);
            throw $e;
        }

    }

    public function getById(int $id): Document
    {
        return Document::findOrFail($id);
    }

    public function getAll(): Collection
    {
        return Document::all();
    }

    public function pdf(int $id): PDF
    {
        try{
            $data = [
                'document' => Document::findOrFail($id)
            ];

            return PDF::loadView('pdf-document',$data);
        } catch (\Exception $e){
            log::error('Generate PDF Document Exception');
            log::error($e);
            throw $e;
        }
    }
}
