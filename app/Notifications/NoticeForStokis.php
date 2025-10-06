<?php

namespace App\Notifications;

use App\Models\Notice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;

class NoticeForStokis extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $title;
    public $message;
    public $creatorId;
    public $url;
    public $warehouseId;
    public $category;

    public function __construct($title, $message, $creatorId, $url, $warehouseId, $category = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->creatorId = $creatorId;
        $this->url = $url;
        $this->warehouseId = $warehouseId;
        $this->category = $category ?? (
            str_contains(strtolower($title), 'KIRIM') ? 'Permintaan Barang' : 'Notice'
        );
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast']; // simpan & kirim realtime
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'category' => $this->category,

        ];
    }

    public function broadcastOn()
    {
        // kirim ke channel berdasarkan warehouse
        return ['warehouse.' . $this->warehouseId];
    }

    public function broadcastAs()
    {
        return 'notice.created';
    }
}