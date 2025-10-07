<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\DashboardController as ApiDashboardController;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan data awal.
     */
    public function index()
    {
        $apiController = new ApiDashboardController();
        $initialData = $apiController->getData()->getData(true);

        return view('dashboard', [
            'employeeInsideCount' => $initialData['employeeInsideCount'],
            'guestInsideCount'    => $initialData['guestInsideCount'],
            'totalInsideCount'    => $initialData['totalInsideCount'],
            'employeesInside'     => $initialData['employeesInside'],
            'guestsInside'        => $initialData['guestsInside'],
        ]);
    }
}