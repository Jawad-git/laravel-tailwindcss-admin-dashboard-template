<?php

namespace App\Services;

use App\Models\Language;
use App\Http\Resources\LanguageCollection;

class LanguageManagementService
{
    public static function getLanguages()
    {
        $languages = Language::all();
        $langCollection = LanguageCollection::collection($languages);
        return $langCollection->response()->getData(true);
    }
}
