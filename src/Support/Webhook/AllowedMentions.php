<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Support\Webhook;

use JsonSerializable;
use JustinKluever\DiscordWebhookBuilder\Enums\Support\AllowedMentionType;

class AllowedMentions implements JsonSerializable
{
    /**
     * @param  AllowedMentionType[]  $parse
     * @param  string[]|int[]  $roles
     * @param  string[]|int[]  $users
     */
    protected function __construct(
        protected array $parse = [],
        protected array $roles = [],
        protected array $users = [],
        protected ?bool $repliedUser = null,
    ) {}

    public static function make(AllowedMentionType ...$parse): self
    {
        return new self(parse: array_values($parse));
    }

    public static function none(): self
    {
        return new self(parse: []);
    }

    /**
     * Allow ping to specific role ids. Ignored when {@see AllowedMentionType::Roles} is set.
     */
    public function roles(string|int ...$roleIds): static
    {
        foreach ($roleIds as $id) {
            $this->roles[] = $id;
        }

        return $this;
    }

    /**
     * Allow ping to specific user ids. Ignored when {@see AllowedMentionType::Users} is set.
     */
    public function users(string|int ...$userIds): static
    {
        foreach ($userIds as $id) {
            $this->users[] = $id;
        }

        return $this;
    }

    public function repliedUser(bool $repliedUser = true): static
    {
        $this->repliedUser = $repliedUser;

        return $this;
    }

    /**
     * @return array{
     *     parse: string[],
     *     roles?: array<string|int>,
     *     users?: array<string|int>,
     *     replied_user?: bool
     * }
     */
    public function jsonSerialize(): array
    {
        $parseValues = array_map(
            static fn (AllowedMentionType $t): string => $t->value,
            $this->parse,
        );

        if ($this->roles !== [] && in_array(AllowedMentionType::Roles, $this->parse, true)) {
            $this->roles = [];
        }

        if ($this->users !== [] && in_array(AllowedMentionType::Users, $this->parse, true)) {
            $this->users = [];
        }

        return array_filter([
            'parse' => $parseValues,
            'roles' => $this->roles ?: null,
            'users' => $this->users ?: null,
            'replied_user' => $this->repliedUser ?: null,
        ], static fn (mixed $v): bool => $v !== null);
    }
}
