<?php

namespace App\Views;

class UserView
{
    /**
     * Render home view data
     */
    public function renderHome()
    {
        return [
            'title' => 'Toko Online',
            'description' => 'Selamat datang di toko kami',
        ];
    }

    /**
     * Render menu view data
     */
    public function renderMenu($menus, $cart = [])
    {
        return [
            'menus' => $menus,
            'cart' => $cart,
            'cartCount' => count($cart),
        ];
    }

    /**
     * Render order detail view data
     */
    public function renderOrderDetail($cart, $total)
    {
        return [
            'items' => $cart,
            'total' => $total,
            'formattedTotal' => number_format($total, 0, ',', '.'),
        ];
    }

    /**
     * Render payment view data
     */
    public function renderPayment($reservation, $paymentUrl)
    {
        return [
            'reservation' => $reservation,
            'paymentUrl' => $paymentUrl,
        ];
    }
}
