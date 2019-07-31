export default {
    methods: {
        list_representation: function (list) {
            var result = '<ol style="list-style-type: ' + list.meta.style + '">';
            var list_items = list.list_items;

            for (var i = 0; i < list_items.length; i++) {
                var list_item = list.list_items[i];
                if (list_item.meta !== undefined && list_item.meta.html !== '' && list_item.meta.html !== null)
                    result += '<li>' + list_item.meta.html;
                else
                    result += '<li>' + list_item.value;

                var temp_list = list_item.ordered_list;

                for (var j = 0; j < temp_list.length; j++) {
                    result += this.list_representation(temp_list[j]);
                }
                result += '</li>';

                
            }
            result += '</ol>';
            return result;
        },

        cast_to_list: function (data) {
            var vm = this;


            var list = new OrderedList();
            list.meta = data.meta;



            data.list_items.forEach(function (list_item) {

                var temp_list_item = new ListItem();
                temp_list_item.value = list_item.value;

                if (list_item.meta !== undefined)
                    temp_list_item.meta = list_item.meta;

                var temp_list = list_item.ordered_list;
                if (temp_list.length !== 0) {
                    if ((temp_list instanceof OrderedList) === false) {
                        temp_list.forEach(function (li) {
                            temp_list_item.addOrderedList(vm.cast_to_list(li));
                        });
                    }
                }

                //temp_list_item.list = temp_list;
                list.addListItem(temp_list_item);
            });


            return list;
        }

    }
}
