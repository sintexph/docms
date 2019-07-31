export class ParagraphHelper {

    static nl2br(str, is_xhtml) {
        if (typeof str === 'undefined' || str === null) {
            return '';
        }
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    }
    static format_html(str) {
        var value = this.nl2br(str);

        value = value.replace(/<bold>/g, "<b>");
        value = value.replace(/<\/bold>/g, "</b>");

        value = value.replace(/<italic>/g, "<i>");
        value = value.replace(/<\/italic>/g, "</i>");
        return value;
    }
}
