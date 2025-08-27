document.addEventListener('DOMContentLoaded', () => {
    function maskIfExists(el, options) {
        if (el instanceof HTMLElement) {
            if (!el._imask) el._imask = IMask(el, options);
            return el._imask;
        }
        return null;
    }

    maskIfExists(document.getElementById('regexp-mask'), {
        mask: /^[1-6]\d{0,5}$/
    });

    maskIfExists(document.getElementById('phone-mask'), {
        mask: '+{7}(000)000-00-00'
    });

    maskIfExists(document.getElementById('number-mask'), {
        mask: Number,
        min: -10000,
        max: 10000,
        thousandsSeparator: ' '
    });

    maskIfExists(document.getElementById('date-mask'), {
        mask: Date,
        min: new Date(1990, 0, 1),
        max: new Date(2020, 0, 1),
        lazy: false
    });

    maskIfExists(document.getElementById('dynamic-mask'), {
        mask: [
            { mask: '+{7}(000)000-00-00' },
            { mask: /^\S*@?\S*$/ }
        ]
    });

    maskIfExists(document.getElementById('currency-mask'), {
        mask: 'Rp. num',
        blocks: {
            num: {
                mask: Number,
                scale: 2,
                thousandsSeparator: '.',
                radix: ',',
                mapToRadix: [',', '.'],
                normalizeZeros: true,
                padFractionalZeros: true
            }
        }
    });

    document.querySelectorAll('.currency-rupiah-mask').forEach((el) => {
        maskIfExists(el, {
            mask: 'Rp. num',
            blocks: {
                num: {
                    mask: Number,
                    scale: 0,
                    thousandsSeparator: '.',
                    radix: ',',
                    mapToRadix: [',', '.'],
                    normalizeZeros: true,
                    padFractionalZeros: true
                }
            }
        });
    });
});