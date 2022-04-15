<?php

namespace Rxak\Framework\Session;

class MessageBag
{
    private static ?MessageBag $messageBag = null;

    private const SESSION_KEY = 'rxak.message_bag';

    public array $values = [];

    public array $newValues = [];

    private function __construct()
    {
        $this->restore();
    }

    public static function init(): void
    {
        self::$messageBag = new MessageBag();
    }

    public static function getInstance(): ?MessageBag
    {
        return self::$messageBag;
    }

    private function restore(): void
    {
        $this->values = Session::get(self::SESSION_KEY, []);

        Session::delete(self::SESSION_KEY);
    }

    public function set(string $name, mixed $value): void
    {
        $this->newValues[$name] = $value;
    }

    public function get(string $name, mixed $default = null): mixed
    {
        return $this->exists($name) ? $this->values[$name] : $default;
    }

    public function exists(string $name): bool
    {
        return isset($this->values[$name]);
    }

    public function delete(string $name): void
    {
        unset($this->values[$name]);
    }

    public function terminate(): void
    {
        $this->set('rxak.previous_url', $_SERVER['REQUEST_URI']);

        Session::set(self::SESSION_KEY, $this->newValues);
    }

    public function hasValidationError(string $fieldName): bool
    {
        return isset(
            $this->values['rxak.validation_errors'],
            $this->values['rxak.validation_errors'][$fieldName]
        );
    }
}