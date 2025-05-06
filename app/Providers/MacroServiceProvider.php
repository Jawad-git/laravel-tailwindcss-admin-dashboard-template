<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

use Carbon\Carbon;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('searchMany', function ($fields, $string, $perPage = 10) {
            if ($string) {
                $this->where(function ($query) use ($fields, $string) {
                    foreach ($fields as $field) {
                        // Check if the field contains a dot, indicating a relation
                        if (strpos($field, '.') !== false) {
                            [$relation, $relationField] = explode('.', $field, 2);
                            // Search in the related model using whereHas
                            $query->orWhereHas($relation, function ($query) use ($relationField, $string) {
                                $query->where($relationField, 'like', '%' . $string . '%');
                            });
                        } else {
                            // Field is in the main table
                            $query->orWhere($field, 'like', '%' . $string . '%');
                        }
                    }
                });
            }

            return $this->orderby($this->model->getKeyName(), 'desc')->paginate($perPage);
        });

        Builder::macro('searchUsersWithFilters', function ($fields, $filters = [], $perPage = 10) {
            return $this->where(function ($query) use ($fields, $filters) {
                $searchApplied = false;

                foreach ($filters as $filterField => $filterValue) {
                    if ($filterField == 'search' && $filterValue) {
                        $query->where(function ($subQuery) use ($fields, $filterValue) {
                            foreach ($fields as $index => $value) {
                                if ($value === 'fullname') {
                                    $subQuery->orWhere(DB::raw("CONCAT(firstname, ' ', lastname)"), 'like', '%' . $filterValue . '%');
                                } elseif ($value !== 'fullname') {
                                    $subQuery->orWhere($value, 'like', '%' . $filterValue . '%');
                                }
                            }
                        });

                        $searchApplied = true;
                    }

                    // Correctly filter using created_at instead of from_date and to_date as column names
                    if ($filterField === 'from_date' && !empty($filterValue)) {
                        $query->whereDate('created_at', '>=', Carbon::parse($filterValue)->toDateString());
                    }

                    if ($filterField === 'to_date' && !empty($filterValue)) {
                        $query->whereDate('created_at', '<=', Carbon::parse($filterValue)->toDateString());
                    }

                    // Other filters
                    if (!in_array($filterField, ['search', 'from_date', 'to_date']) && !empty($filterValue)) {
                        $query->where($filterField, $filterValue);
                    }
                }

                if (!$searchApplied) {
                    $query->whereNotNull('id');
                }
            })->orderby(App::make($this)->getKeyName(), 'desc')->paginate($perPage);
        });

        Builder::macro('exportUsersWithFilters', function ($fields, $filters = [], $perPage = null) {
            $q = $this->where(function ($query) use ($fields, $filters) {
                $searchApplied = false;

                foreach ($filters as $filterField => $filterValue) {
                    if ($filterField === 'search' && $filterValue) {
                        $query->where(function ($subQuery) use ($fields, $filterValue) {
                            foreach ($fields as $value) {
                                if ($value === 'fullname') {
                                    $subQuery->orWhere(DB::raw("CONCAT(firstname, ' ', lastname)"), 'like', '%' . $filterValue . '%');
                                } else {
                                    $subQuery->orWhere($value, 'like', '%' . $filterValue . '%');
                                }
                            }
                        });
                        $searchApplied = true;
                    }

                    // Correctly filter using created_at instead of from_date and to_date as column names
                    if ($filterField === 'from_date' && !empty($filterValue)) {
                        $query->whereDate('created_at', '>=', Carbon::parse($filterValue)->toDateString());
                    }

                    if ($filterField === 'to_date' && !empty($filterValue)) {
                        $query->whereDate('created_at', '<=', Carbon::parse($filterValue)->toDateString());
                    }

                    // Other filters
                    if (!in_array($filterField, ['search', 'from_date', 'to_date']) && !empty($filterValue)) {
                        $query->where($filterField, $filterValue);
                    }
                }

                if (!$searchApplied) {
                    $query->whereNotNull('id');
                }
            });

            return $perPage !== null
                ? $q->orderBy(App::make($this)->getKeyName(), 'desc')->paginate($perPage)
                : $q->orderBy(App::make($this)->getKeyName(), 'desc')->get();
        });
    }
}
