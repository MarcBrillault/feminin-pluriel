<?php

namespace Embryo;

class FemininPluriel
{
    /**
     * @var bool
     */
    private $feminine = false;

    /**
     * @var bool
     */
    private $plural = false;

    /**
     * @var string
     */
    private $text;

    private const REGEX_FP = '#^(.*)(\([FP]{1,2}\))(.*)$#';
    private const REGEX_GENDER = '#\[(.*)\|(.*)\]#U';
    private const REGEX_GENDER_PLURAL = '#\[(.*)\|(.*)\|(.*)\|(.*)\]#U';
    private const MARKER = '#^(.*)(\([FP]{1,2}\))(.*)$#';

    public function adapt(string $text): string
    {
        $this->text = $text;
        $this->setFeminineAndPlural();
        $this->applyGenderAndPlural();
        $this->applyGender();

        return $this->text;
    }

    private function setFeminineAndPlural(): void
    {
        if (preg_match(self::REGEX_FP, $this->text, $matches)) {
            $this->feminine = strpos($matches[2], 'F') !== false;
            $this->plural = strpos($matches[2], 'P') !== false;
            $this->text = trim(str_replace($matches[2], '', $this->text));
        }
    }

    private function applyGenderAndPlural(): void
    {
        preg_replace_callback(
            self::REGEX_GENDER_PLURAL,
            function ($matches) {
                if ($this->feminine) {
                    $replace = $this->plural ? $matches[4] : $matches[2];
                } else {
                    $replace = $this->plural ? $matches[3] : $matches[1];
                }
                $this->text = str_replace($matches[0], $replace, $this->text);
            },
            $this->text
        );
    }

    private function applyGender(): void
    {
        preg_replace_callback(
            self::REGEX_GENDER,
            function ($matches) {
                $replace = $this->feminine ? $matches[2] : $matches[1];
                $this->text = str_replace($matches[0], $replace, $this->text);
            },
            $this->text
        );
    }

    /**
     * Returns an array separating the marker (FP) of the rest of the text
     * The returned array can either have :
     *  - one element when no marker has been found
     *  - two elements when the marker is at the beginning of the text
     *  - three elements when the marker is in the middle of the text
     *
     * @return list<string>
     */
    public function split(string $str): array
    {
        if (preg_match(self::MARKER, $str, $matches)) {
            unset($matches[0]);
            return array_values(array_filter($matches));
        }


        return [$str];
    }
}
