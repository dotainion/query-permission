<?php

namespace permission\infrastructure;

class RoleAttributes{
    const ADMIN = 'admin';
    const MODERATOR = 'moderator';
    const MEMBER = 'member';
    const GUEST = 'guest';
    
    public static function all(): array{
        return [
            self::ADMIN,
            self::MODERATOR,
            self::MEMBER,
            self::GUEST,
        ];
    }
}
