export default {
    methods: {
        cast_to_content: function (data) {
            // Cast a content to an instance of Content Class
            if ((data instanceof Content) === false) {
                var content = new Content;
                content.style = data.style;

                for (let i = 0; i < data.content_items.length; i++) {
                    content.addContentItem(this.cast_to_content_item(data.content_items[i]));
                }


                return content;
            } else
                return data;
        },
        cast_to_content_item: function (data) {
            // Cast a content to an instance of Content Class
            if ((data instanceof ContentItem) === false) {
                var content_item = new ContentItem;
                content_item.name = data.name;
                content_item.box_hidden = data.box_hidden;
                content_item.meta = data.meta;

                for (let i = 0; i < data.data.length; i++) {
                    content_item.addDatum(this.cast_to_datum(data.data[i]));
                }

                return content_item;
            } else
                return data;
        },
        cast_to_datum: function (data) {
            var datum = new Datum;
            switch (data.type) {
                case 'list':
                    datum = this.cast_to_list(data);

                    break;

                case 'image':
                    datum = this.cast_to_image(data);
                    break;

                case 'table':
                    datum = this.cast_to_table(data);
                    break;

                case 'paragraph':
                    datum = this.cast_to_paragraph(data);
                    break;

            }

            datum.type = data.type;
            datum.meta = data.meta;
            datum.hidden = data.hidden;

            return datum;
        },
        cast_to_list: function (data) {
            var vm = this;

            var list = new OrderedList();
            list.has_parent = data.has_parent;
            list.meta = data.meta;

            data.list_items.forEach(function (list_item) {

                var temp_list_item = new ListItem();
                temp_list_item.data = vm.cast_to_datum(list_item.data);

                temp_list_item.is_list = list_item.is_list;

                if (list_item.meta !== undefined)
                    temp_list_item.meta = list_item.meta;

                var temp_list = list_item.ordered_list;

                temp_list.forEach(function (li) {
                    //temp_list_item.addOrderedList(vm.cast_to_datum(li));
                    temp_list_item.addOrderedList(vm.cast_to_list(li));
                });

                list.addListItem(temp_list_item);
            });


            return list;
        },
        cast_to_table: function (data) {
            var vm = this;


            var rows = [];
            var header = [];

            data.rows.forEach(element => {
                var row = [];
                element.forEach(cell => {
                    row.push(vm.cast_to_cell(cell));
                });
                rows.push(row);
            });

            data.header.forEach(element => {

                header.push(vm.cast_to_cell(element));
            });


            return new Table(header, rows);
        },
        cast_to_paragraph: function (data) {
            var temp = new Paragraph;
            temp.value = data.value;
            return temp;
        },
        cast_to_image: function (data) {
            var temp = new Image;
            temp.upload_id = data.upload_id;
            return temp;
        },
        cast_to_cell: function (data) {
            return new TableCell(data.value, data.fit);
        }
    }
}
