<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Message;
use Auth;
use Illuminate\Support\Facades\Input;

class AdminController extends Controller
{
    private $model = null;

    public function __construct() {
        $this->data = [
            'scripts' => [
            ],
            'lte_scripts' => [
            ],
            'styles' => [
            ],
            'lte_styles' => [
            ],
            'meta' => [
                'title' => 'Stall shark admin',
                'keywords' => '',
                'description' => '',
            ],
        ];

    }

    public function getModel(){
        if($this->model == null){
            $this->model = \App::make('App\\'.$this->model_name);
        }

        return $this->model;
    }

    public function getData(){
        return $this->getModel()->all();
    }

    public function index(){
        $this->data['list']['data'] = $this->getData();

        return view('admin.list', $this->data);
    }

    public function add(){
        return view('admin.add', $this->data);
    }

    public function save(){
        $data = Input::all();

        foreach($data as $key => $value){
            if($key == 'password'){
                $data[$key] = bcrypt($value);
            }
        }

        $this->getModel()->create($data);

        return redirect('/admin/'.$this->data['page']);
    }

    public function edit($id){
        $this->data['item'] = $this->getModel()->find($id);

        return view('admin.edit', $this->data);
    }

    public function update($id){
        $item = $this->getModel()->find($id);
        $data = Input::all();

        if(isset($data['password']) && $data['password']){
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $item->fill($data)
            ->save();

        return redirect('/admin/'.$this->data['page']);
    }

    public function view($id){
        $this->data['item'] = $this->getModel()->find($id);

        return view('admin.'.$this->data['page'].'-view', $this->data);
    }

    public function delete($id){
        $this->getModel()->find($id)->delete($id);

        return redirect()->back();
    }
}
