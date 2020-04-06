<?php

namespace App\Helpers\DocumentContent\ContentType;
use App\Helpers\DocumentContent\Datum;
use App\Upload;

class Image extends Datum
{
    public $upload_id;

    public function __construct($upload_id=null)
    {
        $this->type = 'image';

        $this->upload_id=$upload_id;

        $this->meta =[
            'width'=>'500',
            'height'=>null,
            'position'=>'left', // center,left,right,
            'class'=>'img-responsive',
            'style'=>'',
        ];
    }

    
    public function generate_style() {
        $style = '';
        if ($this->meta['width'] != '' && $this->meta['width'] != null)
            $style .= 'width:' . $this->meta['width'] . 'px;';

        if ($this->meta['height'] != '' && $this->meta['height'] != null) {
            $style .= 'height:' . $this->meta['height'] . 'px;';
        }

        if($this->meta['position']!="right")
            $style.='margin-top:10px';
            
        return $style;
    }
    
    public function generateLink()
    {
        
        if(config('app.env')=='production')
        {
            $file=Upload::find($this->upload_id);
            return 'data:image/'.$file->file_type.';base64,'.base64_encode(file_get_contents(\Config::get('app.url').'/file/'.$file->id));
        }
        else
            return 'data:image/png;base64,'.base64_encode(file_get_contents('http://cdn.sportscity.com.ph/images/no-img.jpg'));
    }

    public function toString(){

        $display='';
        switch ($this->meta['position']) {
            case 'center':

                $display.='<center>';
                $display.='<img class="img-responsive" style="' . $this->generate_style() . '" src="' . $this->generateLink() . '">';
                $display.='</center>';

                break;
            case 'left':

                # Set clearfix for floating
                $display='<div class="clearfix"></div>';
                $display.='<img class="img-responsive" style="' . $this->generate_style() . ' float:left;" src="' . $this->generateLink() . '">';
                # Set clearfix for floating
                $display.='<div class="clearfix"></div>';

                break;
            case 'right':

                # Set clearfix for floating
                $display='<div class="clearfix"></div>';
                $display.='<img class="img-responsive" style="' . $this->generate_style() . ' float:right;" src="' . $this->generateLink() . '">';
                # Set clearfix for floating
                $display.='<div class="clearfix"></div>';

                break;
        }
        return $display;
    }
    
}