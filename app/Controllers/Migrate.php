<?php 

namespace App\Controllers;

class Migrate extends \CodeIgniter\Controller
{

    public function index()
    {
        $migrate = \Config\Services::migrations();

        $migrate->latest();
    }

    public function regress($batch = -1)
    {
        $migrate = \Config\Services::migrations();

        $migrate->regress($batch);
    }

    public function refresh()
    {
        $migrate = \Config\Services::migrations();

        $migrate->regress(0);
        $migrate->latest();
    }

}