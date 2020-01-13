import {
    Content
}
from './Content.js';

import {
    ContentItem
}
from './ContentItem.js';



import {
    Datum
}
from './Datum.js';



// Content types import
import {
    TableCell
} from "./ContentType/TableCell";

import {
    Table
}
from './ContentType/Table.js';

import {
    Paragraph
}
from './ContentType/Paragraph.js';

import {
    Image
}
from './ContentType/Image.js';



import {
    OrderedList
}
from './ContentType/OrderedList/OrderedList.js';
import {
    ListItem
}
from './ContentType/OrderedList/ListItem.js';


window.Content = Content;
window.ContentItem = ContentItem;

//Content types Initialization
window.Table = Table;
window.Paragraph = Paragraph;
window.OrderedList = OrderedList;
window.ListItem = ListItem;
window.Image = Image;
window.Datum = Datum;
window.TableCell = TableCell;

