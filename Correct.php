<?php

namespace uzdevid\korrektor;

/**
 * @property string $language
 * @property string $text
 * @property-read array $mistakes
 */
class Correct extends BaseKorrektor {
    public const LANGUAGE_LAT = 'lat';
    public const LANGUAGE_CYR = 'cyr';

    private string $_language = 'lat';
    private string $_text;
    private array $_mistakes = [];

    public function isCorrect() {
        if (empty($this->text)) {
            throw new Exception('Text is required');
        }

        $raw = [
            'content' => $this->text
        ];

        $url = $this->url . '/' . $this->language;

        $response = $this->curlExecute($url, $raw);

        if (!isset($response['content'])) {
            return true;
        }

        $this->_mistakes = $response['content'];
        return empty($this->_mistakes);
    }

    public function getLanguage(): string {
        return $this->_language;
    }

    public function setLanguage(string $language): static {
        $this->_language = $language;
        return $this;
    }

    public function getText(): string {
        return $this->_text;
    }

    public function setText(string $text): static {
        $this->_text = $text;
        return $this;
    }

    public function getMistakes(): array {
        return $this->_mistakes;
    }

    public function highlight(): string {
        $replacements = [];

        foreach ($this->mistakes as $mistake) {
            $misspelled = $mistake["misspelled"];
            $suggestions = implode(', ', $mistake["suggestions"]);
            $replacement = "<span style='color: red;' title='{$suggestions}'>$misspelled</span>";
            $replacements[$misspelled] = $replacement;
        }

        $html = str_replace(array_keys($replacements), array_values($replacements), $this->text);

        return $html;
    }

}