<?php

namespace mkw;

class translate
{
    private array $messages = [];
    private string $locale = 'hu_hu';

    public function __construct(array|string|null $options = null, mixed $content = null, ?string $locale = null)
    {
        if (is_array($options)) {
            $this->addTranslation($options);

            if (isset($options['locale'])) {
                $this->setLocale($options['locale']);
            }

            return;
        }

        if ($options === 'array') {
            $this->addTranslation([
                'adapter' => 'array',
                'content' => $content,
                'locale' => $locale ?: $this->locale,
            ]);
        }
    }

    public function _(string $messageId): string
    {
        return $this->translate($messageId);
    }

    public function translate(string $messageId, ?string $locale = null): string
    {
        $locale = self::normalizeLocale($locale ?: $this->locale);

        if (isset($this->messages[$locale][$messageId])) {
            return $this->messages[$locale][$messageId];
        }

        return $messageId;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = self::normalizeLocale($locale);

        return $this;
    }

    public function addTranslation(array $options): self
    {
        $adapter = $options['adapter'] ?? null;
        $content = $options['content'] ?? null;
        $locale = self::normalizeLocale($options['locale'] ?? $this->locale);

        if ($adapter !== 'array') {
            throw new \InvalidArgumentException(
                "Unsupported translation adapter: {$adapter}"
            );
        }

        $messages = $this->loadArrayContent($content);

        if (!isset($this->messages[$locale])) {
            $this->messages[$locale] = [];
        }

        $this->messages[$locale] = array_replace(
            $this->messages[$locale],
            $messages
        );

        return $this;
    }

    private function loadArrayContent(mixed $content): array
    {
        if (is_array($content)) {
            return $content;
        }

        if (!is_string($content)) {
            throw new \InvalidArgumentException('Translation content must be an array or a PHP file path.');
        }

        if (!is_file($content)) {
            throw new \RuntimeException("Translation file not found: {$content}");
        }

        $messages = require $content;

        if (!is_array($messages)) {
            throw new \RuntimeException("Translation file must return an array: {$content}");
        }

        return $messages;
    }

    public static function normalizeLocale(string|null $locale): string
    {
        if ($locale === null) {
            return $locale;
        }
        return strtolower(str_replace('-', '_', $locale));
    }
}