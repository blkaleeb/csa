<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Pusher\Pusher;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseBase($data, $status = 200)
    {
        $responsebase["status"] = $status;
        $responsebase["time"] = date("d-m-Y H:i:s");
        $responsebase["data"] = $data;
        return response()->json($responsebase, $status);
    }

    public function sendPrintPenjualan($id)
    {
        $options = array(
            'cluster' => 'ap1',
            'useTLS' => true
        );
        $pusher = new Pusher(
            getenv("PUSHER_APP_KEY"),
            getenv("PUSHER_APP_SECRET"),
            getenv("PUSHER_APP_ID"),
            $options
        );

        $data['id'] = $id;
        $pusher->trigger('sendPrinting', 'penjualan', $data);
    }

    public function sendPrintReturPenjualan($id)
    {
        $options = array(
            'cluster' => 'ap1',
            'useTLS' => true
        );
        $pusher = new Pusher(
            getenv("PUSHER_APP_KEY"),
            getenv("PUSHER_APP_SECRET"),
            getenv("PUSHER_APP_ID"),
            $options
        );

        $data['id'] = $id;
        $pusher->trigger('sendPrinting', 'returpenjualan', $data);
    }

    public function sendPrintReturPembelian($id)
    {
        $options = array(
            'cluster' => 'ap1',
            'useTLS' => true
        );
        $pusher = new Pusher(
            getenv("PUSHER_APP_KEY"),
            getenv("PUSHER_APP_SECRET"),
            getenv("PUSHER_APP_ID"),
            $options
        );

        $data['id'] = $id;
        $pusher->trigger('sendPrinting', 'returpembelian', $data);
    }
}
