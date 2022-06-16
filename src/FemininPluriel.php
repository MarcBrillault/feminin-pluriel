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

    private const REGEX_FP = '#^(\(F?P?\))(.*)$#';
    private const REGEX_GENDER = '#\[(.*)\|(.*)\]#U';
    private const REGEX_GENDER_PLURAL = '#\[(.*)\|(.*)\|(.*)\|(.*)\]#U';

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
            $this->feminine = strpos($matches[1], 'F') !== false;
            $this->plural = strpos($matches[1], 'P') !== false;
            $this->text = trim($matches[2]);
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
}
