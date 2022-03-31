<?php

declare(strict_types=1);

namespace VaxPex\form;

use pocketmine\form\Form;
use pocketmine\player\Player;

class CustomForm implements Form
{

    private array $data = [];

    private array $labelMap = [];

    private $callback;

    public function __construct(callable $callback) {
        $this->callback = $callback;
        $this->data["type"] = "custom_form";
        $this->data["title"] = "";
        $this->data["content"] = [];
    }

    public function setTitle(string $title) : void {
        $this->data["title"] = $title;
    }

    public function addLabel(string $text, ?string $label = null) : void {
        $this->addContent(["type" => "label", "text" => $text]);
        $this->labelMap[] = $label ?? count($this->labelMap);
    }

    public function addToggle(string $text, bool $default = null, ?string $label = null) : void {
        $content = ["type" => "toggle", "text" => $text];
        if($default !== null) {
            $content["default"] = $default;
        }
        $this->addContent($content);
        $this->labelMap[] = $label ?? count($this->labelMap);
    }

    public function addSlider(string $text, int $min, int $max, int $step = -1, int $default = -1, ?string $label = null) : void {
        $content = ["type" => "slider", "text" => $text, "min" => $min, "max" => $max];
        if($step !== -1) {
            $content["step"] = $step;
        }
        if($default !== -1) {
            $content["default"] = $default;
        }
        $this->addContent($content);
        $this->labelMap[] = $label ?? count($this->labelMap);
    }

    public function addStepSlider(string $text, array $steps, int $defaultIndex = -1, ?string $label = null) : void {
        $content = ["type" => "step_slider", "text" => $text, "steps" => $steps];
        if($defaultIndex !== -1) {
            $content["default"] = $defaultIndex;
        }
        $this->addContent($content);
        $this->labelMap[] = $label ?? count($this->labelMap);
    }

    public function addDropdown(string $text, array $options, int $default = null, ?string $label = null) : void {
        $this->addContent(["type" => "dropdown", "text" => $text, "options" => $options, "default" => $default]);
        $this->labelMap[] = $label ?? count($this->labelMap);
    }

    public function addInput(string $text, string $placeholder = "", string $default = null, ?string $label = null) : void {
        $this->addContent(["type" => "input", "text" => $text, "placeholder" => $placeholder, "default" => $default]);
        $this->labelMap[] = $label ?? count($this->labelMap);
    }

    private function addContent(array $content) : void {
        $this->data["content"][] = $content;
    }

    public function handleResponse(Player $player, $data) : void {
        if(is_array($data)) {
            $new = [];
            foreach ($data as $i => $v) {
                $new[$this->labelMap[$i]] = $v;
            }
            $data = $new;
        }
        $callback = $this->callback;
        $callback($player, $data);
    }

    public function jsonSerialize()
    {
        return $this->data;
    }
}