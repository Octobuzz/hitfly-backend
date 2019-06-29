<?php

namespace App\Workerman;

use App\Models\UserNotification;
use App\Services\Auth\JsonGuard;
use App\User;
use GatewayWorker\Lib\Gateway;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Events
{
    public static function onWorkerStart($businessWorker)
    {
    }

    public static function onConnect($client_id)
    {
    }

    /**
     * Присоеденение клиента к сокету.
     *
     * @param $client_id
     * @param $data
     */
    public static function onWebSocketConnect($client_id, $data)
    {
        try {
            $user = User::query()->where(JsonGuard::COLUMN_NAME, '=', $data['cookie'][JsonGuard::HEADER_NAME_TOKEN])->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            Gateway::sendToClient($client_id, '{"error_code":401}');
        }
        $user->userNotification()->updateOrCreate(['user_id' => $user->id], ['token_web_socket' => $client_id]);
    }

    /**
     * Получение сообщений через сокет
     *
     * @param $client_id
     * @param $message
     */
    public static function onMessage($client_id, $message)
    {
//        Log::debug($client_id);
//        Log::debug($message);
    }

    /**
     * Отключение от веб сокета.
     *
     * @param string $client_id
     */
    public static function onClose($client_id)
    {
        $notificationUser = UserNotification::query()->where('token_web_socket', '=', $client_id)->first();
        if(empty($notificationUser) === false){
            $notificationUser->token_web_socket = null;
                    $notificationUser->save();
        }


    }

    /**
     * Отсановка веб сокета.
     *
     * @param $businessWorker
     */
    public static function onWorkerStop($businessWorker)
    {
        UserNotification::query()->update(['token_web_socket' => null]);
    }
}
