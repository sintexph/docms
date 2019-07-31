export class Cipher {

    static setLetters() {
        return [
            'a',
            'b',
            'c',
            'd',
            'e',
            'f',
            'g',
            'h',
            'i',
            'j',
            'k',
            'l',
            'm',
            'n',
            'o',
            'p',
            'q',
            'r',
            's',
            't',
            'u',
            'v',
            'w',
            'x',
            'y',
            'z',

        ];
    }
    static decode(index) {
        if (index < 1) {
            return '';
        } else if (index <= this.setLetters().length) {
            return this.setLetters()[index - 1];
        } else if (index > this.setLetters().length) {

            var letters = this.setLetters();
            // Count the occurrence of the letter 
            var occurrence = Math.round(index / letters.length);
            // Get the letter index
            var letter_index = index - (letters.length * Math.floor(index / letters.length));
            var letter = '';

            for (let i = 0; i < occurrence; i++) {
                letter += letters[letter_index - 1];
            }

            return letter;
        }
    }
}
