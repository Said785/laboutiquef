<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classe\Cart;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session', name: 'stripe_create_session',)]
    public function index(Cart $cart): Response
    {
        $products_for_stripe = [];
        $YOUR_DOMAIN = 'https://127.0.0.1:8000';

        foreach ($cart->getFull() as $product) {
            
            $products_for_stripe [] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product['product']->getPrice(),
                    'product_data' => [
                    'name' => $product['product']->getName(),
                    'images' => [$YOUR_DOMAIN."/Upload/images/".$product['product']->getDescription()],
                      ],
                    ],
                    'quantity' => $product['quantity'],
                
            ];

         }
        Stripe::setApiKey('sk_test_51KpCjVIqpgaX9NVkziHMpkM1TJ65FTxmK80ih37SisIJD2fBW7UtTItSUfbNbJddEJXrcqrB30Bh2Eb9qxZGONBg0055frjbil');
       
           
        

        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
             'line_items' => [
                 $products_for_stripe   
             ],
             'mode' => 'payment',
             'success_url' => $YOUR_DOMAIN . '/success.html',
             'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
          
         
            ]);

            $response = new JsonResponse(['id' => $checkout_session->id]);
            return $response;
    }
}
