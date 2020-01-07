<?php

namespace App\SRC\Helpers;


use App\Models\Document;
use Illuminate\Support\Facades\DB;

class DocumentHelper
{
    public function create($attributes) {
        DB::beginTransaction();
        try {
            if (! $this->validDocument($attributes)) {
                if ($document = $this->newDocument($attributes)) {
                    DB::commit();
                    return $document;
                }
                throw new \Exception("Failed to create document, please try again");
            }
            throw new \Exception("Document already in use, please try again");
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }
    }

    private function validDocument($attributes, int $id = 0) {
        if ($id) {
            return optional(Document::query()
                ->where('id', '!=', $id)
                ->where('name', $attributes['name'])
                ->where('document_url', $attributes['document_url']))
                ->first();
        }
        return optional(Document::query()
            ->where('name', $attributes['name'])
            ->where('document_url', $attributes['document_url']))
            ->first();
    }

    private function newDocument($attributes) {
        return Document::query()->create($attributes);
    }

    public function update($id, $attributes) {
        DB::beginTransaction();
        try {
            if ($this->checkDocument($id)) {
                if ($this->validDocument($attributes, $id)) {
                    if ($document = $this->editDocument($attributes, $id)) {
                        DB::commit();
                        return $document;
                    }
                    throw new \Exception("Failed to update document, please try again");
                }
                throw new \Exception('Document already in use, please try again');
            }
            throw new \Exception('Oops, document not found');
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }
    }

    private function checkDocument($id) {
        return optional(Document::query()->where('id', $id))->count();
    }

    private function editDocument($attributes, $id) {
        return Document::query()->where('id', $id)->update($attributes);
    }
}