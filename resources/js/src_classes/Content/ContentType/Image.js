import {
    Datum
}
from '../Datum.js';


export class Image extends Datum {

    constructor(upload_id) {
        super();


        this._type = 'image';

        if (upload_id !== undefined)
            this._upload_id = upload_id;
        else
            // invokes the setter
            this._upload_id = null;

        this._meta = {
            width: '500',
            height: null,
            position: 'left', // center,left,right,
            class: 'img-responsive',
            style: '',
        };
    }
    get upload_id() {
        return this._upload_id;
    }
    set upload_id(upload_id) {
        this._upload_id = upload_id;
    }

    reset() {
        // invokes the setter
        this._upload_id = '';
        this._meta.width = '500';
        this._meta.height = null;
        this._meta.position = 'left';
        this._meta.class = 'img-responsive';

    }


    generate_style() {
        var style = '';
        if (this._meta.width !== '' && this._meta.width !== null)
            style += 'width:' + this._meta.width + 'px;';

        if (this._meta.height !== '' && this._meta.height !== null) {
            style += 'height:' + this._meta.height + 'px;';
        }

        if (this._meta.position !== 'right')
            style += 'margin-top:10px';

        return style;
    }
    generateLink() {
        return '/file/' + this._upload_id;
    }
    toShortString() {
        return '<img style="width:30px;" src="' + this.generateLink() + '">';
    }

    toString() {

        var display = '';
        switch (this._meta.position) {
            case 'center':

                display += '<center>';
                display += '<img class="img-responsive" style="' + this.generate_style() + '" src="' + this.generateLink() + '">';
                display += '</center>';

                break;
            case 'left':

                display = '<div class="clearfix"></div>';
                display += '<img class="img-responsive" style="' + this.generate_style() + ' float:left;" src="' + this.generateLink() + '">';
                display += '<div class="clearfix"></div>';

                break;
            case 'right':
                display = '<div class="clearfix"></div>';
                display += '<img class="img-responsive" style="' + this.generate_style() + ' float:right;" src="' + this.generateLink() + '">';
                display += '<div class="clearfix"></div>';
                break;
        }


        return display;
    }
}
