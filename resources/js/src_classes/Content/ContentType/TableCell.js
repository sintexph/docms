 export class TableCell {
     constructor(value, fit, merge) {

         if (value !== undefined)
             this._value = value;
         else
             this._value = '';


         if (fit !== undefined)
             this._fit = fit;
         else
             this._fit = false;


         this._rowspan = "";
         this._colspan = "";
         this._center = false;



     }

     set colspan(value) {
         this._colspan = value;
     }
     get colspan() {
         return this._colspan;
     }


     set rowspan(value) {
         this._rowspan = value;
     }
     get rowspan() {
         return this._rowspan;
     }



     set center(value) {
         this._center = value;
     }
     get center() {
         return this._center;
     }



     get value() {
         return this._value;
     }
     set value(value) {
         this._value = value;
     }

     get fit() {
         return this._fit;
     }
     set fit(fit) {
         this._fit = fit;
     }




 }
