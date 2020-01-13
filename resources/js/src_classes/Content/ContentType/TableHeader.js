 export class TableHeader {
     constructor(value, fit) {

         if (value !== undefined)
             this._value = value;
         else
             this._value = '';

         if (fit !== undefined)
             this._fit = fit;
         else
             this._fit = false;
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
