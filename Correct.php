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

    public function hightlight(): string {
        $offset = 0;
        $html = "";

        foreach ($this->mistakes as $mistake) {
            $position = $mistake["position"];
            $misspelled = $mistake["misspelled"];
            $suggestions = implode(', ', $mistake["suggestions"]);

            $html .= substr($this->text, $offset, $position - $offset);
            $html .= "<span style='color: red;' title='{$suggestions}'>";

            $html .= substr($this->text, $position, strlen($misspelled));
            $html .= '</span>';

            $offset = $position + strlen($misspelled);
        }

        return $html;
    }
}