<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(Subscription $model)
    {
        parent::__construct($model);
    }
    public function index()
    {
        return $this->success('Successfully retrieved data', $this->model->get());
    }

    public function showSubs()
    {
        $data['title'] = 'Subscription Platform';
         
        $response = $this->index();
        $decoded = $response->getData(true);
        $subscriptions = $decoded['data']; 
        $data['subscriptions'] = $subscriptions;
        // dd($data);

        return view('subscription', $data);
    }

}