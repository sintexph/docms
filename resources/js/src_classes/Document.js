 export class Document {
     constructor(
         number,
         title,
         section,
         system,
         category,
         keywords,
         comment,
         access,
         created_by,
         serial) {


         if (number !== undefined)
             this._number = number;
         else
             this._number = '';

         if (title !== undefined)
             this._title = title;
         else
             this._title = '';


         if (section !== undefined)
             this._section = section;
         else
             this._section = '';

         if (system !== undefined)
             this._system = system;
         else
             this._system = '';


         if (category !== undefined)
             this._category = category;
         else
             this._category = '';



         if (keywords !== undefined)
             this._keywords = keywords;
         else
             this._keywords = '';




         if (comment !== undefined)
             this._comment = comment;
         else
             this._comment = '';

         if (access !== undefined)
             this._access = access;
         else
             this._access = 1;


         if (created_by !== undefined)
             this._created_by = created_by;
         else
             this._created_by = '';


         if (serial !== undefined)
             this._serial = serial;
         else
             this._serial = '';

         this._current_version = new DocumentVersion;

     }


     get current_version() {
         return this._current_version;
     }
     set current_version(current_version) {
         this._current_version = current_version;
     }


     toObject() {
         return {
             title: this.title,
             section: this.section,
             system: this.system,
             category: this.category,
             keywords: this.keywords,
             comment: this.comment,
             access: this.access,
             created_by: this.created_by,
             serial: this.serial,
         }
     }

     get serial() {
         return this._serial;
     }
     set serial(serial) {
         this._serial = serial;
     }

     get access() {
         return this._access;
     }

     set access(access) {
         this._access = access;
     }


     get comment() {
         return this._comment;
     }

     set comment(comment) {
         this._comment = comment;
     }


     get keywords() {
         return this._keywords;
     }

     set keywords(keywords) {
         this._keywords = keywords;
     }


     get created_by() {
         return this._created_by;
     }

     set created_by(created_by) {
         this._created_by = created_by;
     }


     get category() {
         return this._category;
     }

     set category(category) {
         this._category = category;
     }

     get system() {
         return this._system;
     }

     set system(system) {
         this._system = system;
     }


     get section() {
         return this._section;
     }

     set section(section) {
         this._section = section;
     }

     get title() {
         return this._title;
     }

     set title(title) {
         this._title = title;
     }


     get number() {
         return this._number;
     }

     set number(number) {
         this._number = number;
     }


 }
