<?php namespace App\Controllers;

use CodeIgniter\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Tableau de bord',
            'user'  => session()->get(),
        ];
        return view('dashboard/index', $data);
    }
}