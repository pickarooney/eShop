/*
 * Display error message based on current element's data attributes
 */
function cgToggleError(element, status) {
    var errorMessage = $(element).data('validation-error-msg'),
        errorContainer = $(element).data('validation-error-msg-container');

    $(element).removeClass().addClass(status);

    if (status === 'valid') {
        $(errorContainer).html(errorMessage).hide();
    } else if (status === 'invalid') {
        $(errorContainer).html(errorMessage).show();
    }
}

/*
 * Format a date as MM/YY
 */
function cgFormatExpiryDate(e) {
    var inputChar = String.fromCharCode(event.keyCode);
    var code = event.keyCode;
    var allowedKeys = [8];
    if (allowedKeys.indexOf(code) !== -1) {
        return;
    }

    event.target.value = event.target.value.replace(
        /^([1-9]\/|[2-9])$/g, '0$1/' // 3 > 03/
    ).replace(
        /^(0[1-9]|1[0-2])$/g, '$1/' // 11 > 11/
    ).replace(
        /^([0-1])([3-9])$/g, '0$1/$2' // 13 > 01/3
    ).replace(
        /^(0?[1-9]|1[0-2])([0-9]{2})$/g, '$1/$2' // 141 > 01/41
    ).replace(
        /^([0]+)\/|[0]+$/g, '0' // 0/ > 0 and 00 > 0
    ).replace(
        /[^\d\/]|^[\/]*$/g, '' // To allow only digits and `/`
    ).replace(
        /\/\//g, '/' // Prevent entering more than 1 `/`
    );
}

/*
 * Check if date element is valid and add a visual hint
 */
function cgDateValidate(whatDate) {
    var currVal = whatDate;

    if (currVal === '') {
        return false;
    }

    var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
    var dtArray = currVal.match(rxDatePattern);

    if (dtArray == null) {
        return false;
    }

    // Check for dd/mm/yyyy format
    var dtDay = dtArray[1],
        dtMonth= dtArray[3],
        dtYear = dtArray[5];

    if (dtMonth < 1 || dtMonth > 12) {
        return false;
    } else if (dtDay < 1 || dtDay> 31) {
        return false;
    } else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31) {
        return false;
    } else if (dtMonth == 2) {
        var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
        if (dtDay> 29 || (dtDay ==29 && !isleap)) {
            return false;
        }
    }

    return true;
}

/*
 * Credit card expiry date formatting (real-time)
 */
$(document).on('keyup blur', '#expiry', function(event) {
    var currentDate = new Date();
    var currentMonth = ("0" + (currentDate.getMonth() + 1)).slice(-2);
    var currentYear = String(currentDate.getFullYear()).slice(-2);

    var cardExpiryArray = $('#expiry').val().split('/');
    var userMonth = cardExpiryArray[0],
        userYear = cardExpiryArray[1];

    if ($('#expiry').val().length !== 5) {
        cgToggleError($(this), 'invalid');
    } else if (userYear < currentYear) {
        cgToggleError($(this), 'invalid');
    } else if (userYear <= currentYear && userMonth < currentMonth) { cgToggleError($(this), 'invalid'); } else if (userMonth > 12) {
        cgToggleError($(this), 'invalid');
    } else {
        cgToggleError($(this), 'valid');
    }

    cgFormatExpiryDate(event);
});

/*
 * Credit card CVV disallow letters (real-time)
 */
$(document).on('keyup', '#cvv', function(event) {
    event.target.value = event.target.value.replace(/[^\d\/]|^[\/]*$/g, '');
});

/*
 * Credit card CVV length check
 */
$(document).on('blur', '#cvv', function(e) {
    if ($('#cvv').val().length < 3) {
        cgToggleError($(this), 'invalid');
    }
});

/*
 * Credit card validation
 */
function cgCheckLuhn(input) {
    var sum = 0,
        numdigits = input.length;
    var parity = numdigits % 2;

    for (var i=0; i < numdigits; i++) { var digit = parseInt(input.charAt(i)); if (i % 2 == parity) { digit *= 2; } if (digit > 9) {
            digit -= 9;
        }
        sum += digit;
    }

    return (sum % 10) == 0;
}

function cgDetectCard(input) {
    var typeTest = 'u',
        ltest1 = 16,
        ltest2 = 16;
        ltest3 = 'none';

    if (/^4/.test(input)) {
        typeTest = 'v';
        ltest1 = 13;
        ltest3 = 'VISA';
    } else if (/^5[1-5]/.test(input)) {
        typeTest = 'm';
        ltest3 = 'MASTERCARD';
    } else if (/^6(011|4[4-9]|5)/.test(input)) {
        typeTest = 'd';
        ltest3 = 'VISADEBIT';
    }

    return [typeTest,ltest1,ltest2,ltest3];
}

/*
 * Credit card Luhn validation (real-time)
 */
$(document).on('keyup', '#cardnum', function() {
    var val = this.value,
        val = val.replace(/[^0-9]/g, ''),
        detected = cgDetectCard(val),
        errorClass = 'invalid',
        luhnCheck = cgCheckLuhn(val),
        valueCheck = (val.length == detected[1] || val.length == detected[2]);

    if ($('body').hasClass('inline-ab')) {
        cgToggleError($(this), 'invalid');
    }

    if (luhnCheck && valueCheck) {
        errorClass = 'valid';
    } else if (valueCheck || val.length > detected[2]) {
        errorClass = 'invalid';
    }

    if ($('body').hasClass('inline-ab')) {
        cgToggleError($(this), errorClass);
        cgToggleError($(this), 'cc ' + detected[0] + ' ' + errorClass);
    }
    $(this).addClass('cc ' + detected[0] + ' ' + errorClass);
});

/*
 * Credit card digit formatting (real-time)
 */
$(document).on('keypress change blur', '#cardnum', function() {
    $(this).val(function(index, value) {
        return value.replace(/[^a-z0-9]+/gi, '').replace(/(.{4})/g, '$1 ').trim();
    });
});
$(document).on('copy cut paste', '#cardnum', function() {
    setTimeout(function() {
        $('#cardnum').trigger('change');
    });
});