<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

Str::macro('getCurrentDate', function ($format = null) {
  return is_null($format)
  ? Carbon::now()
  : Carbon::now()->translatedFormat($format);
});

Str::macro('now', fn ($fmt = null) =>  is_null($fmt) ? Carbon::now() : Carbon::now()->translatedFormat($fmt));
Str::macro('title', fn ($page = null) =>  is_null($page) ? config('app.name') : $page . " | " . config('app.name'));
Str::macro('encodeBase64', fn ($str, $gztype = ZLIB_ENCODING_RAW) => base64_encode(zlib_encode($str, $gztype, 9)));
Str::macro('decodeBase64', fn ($str) => @zlib_decode(base64_decode($str)));
Str::macro('upl_role', fn () => upl_role());

EloquentBuilder::macro('searchByColumn', function($searchText, $columns) {
  $collections = collect($columns);
  $filters['fields'] = $collections->filter(fn ($q) => mb_strpos($q, '->') === false)->toArray();
  $filters['relations'] = $collections
    ->filter(fn ($q) => mb_strpos($q, '->') !== false)
    ->groupBy(fn ($q) => substr($q, 0, mb_strpos($q, '->')))
    ->map(fn ($v, $k) => $v->map(fn ($d) => str_replace("{$k}->", '', $d)))
    ->toArray();

    $this->where(function($query) use ($searchText, $filters) {
    $idx = 0;
    foreach ($filters as $filterKey => $filter) {
      if ($filterKey == 'fields') {
        foreach ($filter as $colIdx => $column) {
          $clause = $idx == 0 && $colIdx == 0 ? 'where' : 'orWhere';
          $query->{$clause}(DB::raw("lower({$column})"), 'like', '%' . strtolower($searchText) . '%');
        }
      }

      # mapping as relation
      if ($filterKey == 'relations') {
        foreach ($filter as $relation => $relationFields) {
          $clause = $idx == 0 && $colIdx == 0 ? 'whereHas' : 'orWhereHas';
          $query->{$clause}(
            $relation,
            function ($sql) use ($relationFields, $searchText) {
              foreach ($relationFields as $colIdx => $column) {
                $deepClause = $colIdx == 0 ? 'where' : 'orWhere';
                $sql->{$deepClause}(\DB::raw("lower({$column})"), 'like', '%' . strtolower($searchText) . '%');
              }
            }
          );
        }
      }
      ++$idx;
    }
  });
});
