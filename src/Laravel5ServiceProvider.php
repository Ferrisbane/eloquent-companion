<?php

namespace Ferrisbane\EloquentCompanion;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class Laravel5ServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerToQuery();
        $this->registerWithWhereHas();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Register ->toQuery() macro
     *
     * @return void
     */
    protected function registerToQuery()
    {
        Builder::macro('toQuery', function() {
            $query = $this->toSql();
            $bindings = $this->getBindings();
 
            $sql = preg_replace_callback('/(:([0-9a-z_]+)|(\?))/', function($value) use (&$bindings) {
                $data = array_shift($bindings);
 
                if ( ! is_int($data)) {
                    return "'$data'";
                }
 
                return $data;
            }, $query);
 
            return $sql;
        });
    }

    /**
     * Register ->withWhereHas() macro
     *
     * @return void
     */
    protected function registerWithWhereHas()
    {
        Builder::macro('withWhereHas', function($relation, $constraint) {
            return $this->whereHas($relation, $constraint)
                ->with([
                    $relation => $constraint
                ]);
        });
    }
}
