<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genre;


class GenreController extends Controller
{

    public function __construct(Genre $model)
    {
        parent::__construct($model);
    }

    public function index()
    {
        return $this->success('Successfully retrieved data', $this->model->with('promotions')->get());
    }

   
}
