<?php

namespace App\Enum;

enum MessageStatusEnum: string {
    case SENT = 'sent';
    case RECEIVED = 'received';
    case READ = 'read';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
