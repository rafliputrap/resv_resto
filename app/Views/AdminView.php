<?php

namespace App\Views;

class AdminView
{
    /**
     * Render login view data
     */
    public function renderLogin()
    {
        return [
            'title' => 'Admin Login',
            'description' => 'Halaman login administrator',
        ];
    }

    /**
     * Render dashboard view data
     */
    public function renderDashboard($reservations, $totalOmzet, $totalPengunjung, $activeTables)
    {
        return [
            'reservations' => $reservations,
            'totalOmzet' => $totalOmzet,
            'totalPengunjung' => $totalPengunjung,
            'activeTables' => $activeTables,
        ];
    }

    /**
     * Render history view data
     */
    public function renderHistory($history, $totalOmzet, $totalPengunjung)
    {
        return [
            'history' => $history,
            'totalOmzet' => $totalOmzet,
            'totalPengunjung' => $totalPengunjung,
        ];
    }
}
