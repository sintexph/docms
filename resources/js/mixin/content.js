export default {
    methods: {
   
        replace_highlight: function (toReplace, element) {
            
            // obtain the index of the first selected character
            var start = element.selectionStart;
            // obtain the index of the last selected character
            var finish = element.selectionEnd;
            //obtain all Text
            var allText = element.value;

            // obtain the selected text
            var sel = allText.substring(start, finish);
            //append te text;
            var newText = allText.substring(0, start) + "<" + toReplace + ">" + sel + "</" + toReplace + ">" +
                allText.substring(
                    finish, allText.length);

            return newText;
        },
        nl2br: function (str, is_xhtml) {
            if (typeof str === 'undefined' || str === null) {
                return '';
            }
            var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
            return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
        },
        format_html: function (str) {
            var value = this.nl2br(str);

            value = value.replace(/<bold>/g, "<b>");
            value = value.replace(/<\/bold>/g, "</b>");

            value = value.replace(/<italic>/g, "<i>");
            value = value.replace(/<\/italic>/g, "</i>");
            return value;
        },
    }
}
