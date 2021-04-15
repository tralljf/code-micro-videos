<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

abstract class BasicCrudController extends Controller
{
    protected abstract function model();
    protected abstract function rulesUpdate();
    protected abstract function rulesStore();

    public function index()
    {
        return $this->model()::all();
    }


    public function store(Request $request)
    {
        $validateData = $this->validate($request, $this->rulesStore());
        $obj = $this->model()::create($validateData);
        $obj->refresh();

        return $obj;
    }


    protected function findOrFail($id){
        $model = $this->model();
        $keyname = (new $model)->getRouteKeyName();

        return $this->model()::where($keyname, $id)->firstOrFail();
    }

    public function show($id)
    {
        $obj = $this->findOrFail($id);
        return $obj;
    }

    public function update(Request $request, $id)
    {
        $obj = $this->findOrFail($id);
        $validateData = $this->validate($request, $this->rulesUpdate());
        $obj->update($validateData);
        return $obj;
    }

    public function destroy($id)
    {
        $obj = $this->findOrFail($id);
        $obj->delete();

        return response()->noContent();
    }
}
