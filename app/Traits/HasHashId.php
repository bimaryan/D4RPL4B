<?php

namespace App\Traits;

use Hashids\Hashids;

trait HasHashId
{
    /**
     * Get Hashids instance - sinkron untuk semua model.
     * Salt konsisten biar tidak berubah tiap deploy.
     */
    protected static function hashids(): Hashids
    {
        static $instance = null;
        if ($instance === null) {
            $salt = config('hashids.salt', env('HASHIDS_SALT', 'd4rpl4b-polindra-warm-2026-salt'));
            $minLength = config('hashids.min_length', 10);
            $alphabet = config('hashids.alphabet', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
            $instance = new Hashids($salt, $minLength, $alphabet);
        }
        return $instance;
    }

    /**
     * Accessor: $model->hash_id
     */
    public function getHashIdAttribute(): string
    {
        return static::hashids()->encode($this->getKey());
    }

    /**
     * Decode hash -> id (int|null)
     */
    public static function decodeHashId(string $hash): ?int
    {
        $decoded = static::hashids()->decode($hash);
        return !empty($decoded) ? (int) $decoded[0] : null;
    }

    /**
     * Untuk route() helper biar otomatis pakai hashid saat kirim Model.
     */
    public function getRouteKey(): mixed
    {
        return $this->hash_id;
    }

    /**
     * Override route binding: terima hashid dari URL, cari by id asli.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // jika binding pakai field spesifik selain id, fallback normal
        if ($field !== null && $field !== 'id') {
            return parent::resolveRouteBinding($value, $field);
        }

        $id = static::decodeHashId($value);
        if ($id === null) {
            return null; // akan jadi 404
        }

        return static::where('id', $id)->first();
    }
}
