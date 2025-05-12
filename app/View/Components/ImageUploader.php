<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ImageUploader extends Component
{
    public $name, $current, $label;
    public function __construct($name, $current = null, $label = null)
    {
        $this->name = $name;
        $this->current = $current;
        $this->label = $label;
    }
    public function render()
    {
        return view('components.image-uploader');
    }
}
