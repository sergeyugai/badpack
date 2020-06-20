<?php

namespace SergeYugai\Laravel\Badpack\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;

class BaseCrudController extends CrudController
{
    use AuthorizesRequests;
    use ListOperation;
    use CreateOperation;
    use UpdateOperation;
    use DeleteOperation;
    use ShowOperation;

    protected ?string $model = null; // model class FQDN
    protected ?string $route = null; // use this shortcut if you want your path to start with backpack route prefix
    protected ?string $absoluteRoute = null; // use this to specify entire path yourself
    protected ?array $entitySingularPlural = null; // use this to provide singular and plural as array
    protected ?string $entitySingular = null; // use this to provide singular
    protected ?string $entityPlural = null; // use this to provide plural; if null, its guesses from entitySingular

    public function setup()
    {
        parent::setup();

        if ($this->model !== null) {
            $this->crud->setModel($this->model);
        }

        if ($this->absoluteRoute !== null) {
            $this->crud->setRoute($this->absoluteRoute);
        } else if ($this->route) {
            $this->crud->setRoute(config('backpack.base.route_prefix') . $this->route);
        }

        if ($this->entitySingular !== null) {
            $plural = $this->entityPlural ?: Str::plural($this->entitySingular);
            $this->crud->setEntityNameStrings($this->entitySingular, $plural);
        } else if ($this->entitySingularPlural !== null) {
            [$singular, $plural] = $this->entitySingularPlural;
            $this->crud->setEntityNameStrings($singular, $plural);
        } else if ($this->model !== null) {
            $modelName = class_basename($this->model);
            $singular = str_replace('_', ' ', Str::of($modelName)->snake());
            $plural = Str::plural($singular);
            $this->crud->setEntityNameStrings($singular, $plural);
        }
    }

    public function setupCreateOperation(): void
    {
        $this->crud->setCreateView('badpack::create');
    }
}