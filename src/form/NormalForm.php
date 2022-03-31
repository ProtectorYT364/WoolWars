<?php

declare(strict_types=1);

namespace VaxPex\form;

use pocketmine\form\Form;
use pocketmine\player\Player;

class NormalForm implements Form
{

    private array $data = [];

    private array $labels = [];

    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
        $this->data["type"] = "form";
        $this->data["content"] = "";
        $this->data["buttons"] = [];
    }

    public function setTitle(string $text)
    {
        $this->data["title"] = $text;
    }

    public function setContent(string $text)
    {
        $this->data["content"] = $text;
    }

    public function addButton(string $text, string $label = null)
    {
        $this->labels[] = $label ?? count($this->labels);
        $this->data["buttons"][] = ["text" => $text];
    }

    public function handleResponse(Player $player, $data) : void {
        $count = count($this->data["buttons"]);
        if($data >= $count || $data < 0) {
            throw new \Error("Button $data does not exist");
        }
        foreach($this->labels as $label){
            if(is_int($label)){
                continue;
            }
            $data = $this->labels[$data] ?? null;
        }
        $callback = $this->callback;
        $callback($player, $data);
    }

    public function jsonSerialize()
    {
        return $this->data;
    }
}