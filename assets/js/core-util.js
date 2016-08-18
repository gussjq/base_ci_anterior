var CoreUtil = {
    
    
    format: {
        formatNumber: function(num, prefix, decimals, bPonerComas) {
            prefix = prefix || '';
            num = (num == null || num == "") ? "0" : num+"";
            var splitStr = num.split('.');
            var splitLeft = splitStr[0];
            var splitRight = splitStr.length > 1 ? '.' + splitStr[1] : "";
            if (decimals !== undefined) {
                if (splitRight.length - 1 > decimals) {
                    if (decimals == 0) {
                        splitRight = "";
                    } else {
                        splitRight = splitRight.substr(0, decimals + 1);
                    }
                } else {
                    if (splitRight.length == 0 && decimals > 0) {
                        splitRight = ".";
                    }
                    while (splitRight.length <= decimals) {
                        splitRight += "0";
                    }
                }
            }
            
            if ((typeof bPonerComas != "undefined") && (bPonerComas === true))
            {
                var regx = /(\d+)(\d{3})/;
                while (regx.test(splitLeft)) 
                {
                    splitLeft = splitLeft.replace(regx, "$1" + "," + "$2");
                }
            }
            
            return prefix + splitLeft + splitRight;
        },
        unformatNumber: function(num) {
            return num.replace(/([^0-9\.\-])/g, '') * 1;
        },
        padZeros: function(number, length) {
            var str = '' + number;
            while (str.length < length) {
                str = '0' + str;
            }
            return str;
        }
    },
    cookies: {
        cookiesAllowed: function() {
            setCookie('checkCookie', 'test', 1);
            if (this.getCookie('checkCookie')) {
                this.deleteCookie('checkCookie');
                return true;
            }
            return false;
        },
        setCookie: function(name, value, expires, options) {
            if (options === undefined) {
                options = {};
            }
            if (expires) {
                var expires_date = new Date();
                expires_date.setDate(expires_date.getDate() + expires)
            }
            document.cookie = name + '=' + escape(value) +
                    ((expires) ? ';expires=' + expires_date.toGMTString() : '') +
                    ((options.path) ? ';path=' + options.path : '') +
                    ((options.domain) ? ';domain=' + options.domain : '') +
                    ((options.secure) ? ';secure' : '');
        },
        getCookie: function(name) {
            var start = document.cookie.indexOf(name + '=');
            var len = start + name.length + 1;
            if ((!start) && (name != document.cookie.substring(0, name.length))) {
                return null;
            }
            if (start == -1)
                return null;
            var end = document.cookie.indexOf(';', len);
            if (end == -1)
                end = document.cookie.length;
            return unescape(document.cookie.substring(len, end));
        },
        deleteCookie: function(name, path, domain) {
            if (this.getCookie(name)) {
                document.cookie = name + '=' +
                        ((path) ? ';path=' + path : '') +
                        ((domain) ? ';domain=' + domain : '') +
                        ';expires=Thu, 01-Jan-1970 00:00:01 GMT';
            }
        }
    },
    html: {
        htmlEntityEncode: function(str) {
            return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;');
        },
        html_entity_decode: function(string, quote_style) {
            var hash_map = {}, symbol = '', tmp_str = '', entity = '';
            tmp_str = string.toString();

            if (false === (hash_map = this.get_html_translation_table('HTML_ENTITIES', quote_style))) {
                return false;
            }

            delete (hash_map['&']);
            hash_map['&'] = '&amp;';

            for (symbol in hash_map) {
                entity = hash_map[symbol];
                tmp_str = tmp_str.split(entity).join(symbol);
            }
            tmp_str = tmp_str.split('&#039;').join("'");

            return tmp_str;
        },
        get_html_translation_table: function(table, quote_style) {
            var entities = {}, hash_map = {}, decimal = 0, symbol = '';
            var constMappingTable = {}, constMappingQuoteStyle = {};
            var useTable = {}, useQuoteStyle = {};

            // Translate arguments
            constMappingTable[0] = 'HTML_SPECIALCHARS';
            constMappingTable[1] = 'HTML_ENTITIES';
            constMappingQuoteStyle[0] = 'ENT_NOQUOTES';
            constMappingQuoteStyle[2] = 'ENT_COMPAT';
            constMappingQuoteStyle[3] = 'ENT_QUOTES';

            useTable = !isNaN(table) ? constMappingTable[table] : table ? table.toUpperCase() : 'HTML_SPECIALCHARS';
            useQuoteStyle = !isNaN(quote_style) ? constMappingQuoteStyle[quote_style] : quote_style ? quote_style.toUpperCase() : 'ENT_COMPAT';

            if (useTable !== 'HTML_SPECIALCHARS' && useTable !== 'HTML_ENTITIES') {
                throw new Error("Table: " + useTable + ' not supported');
                // return false;
            }

            entities['38'] = '&amp;';
            if (useTable === 'HTML_ENTITIES') {
                entities['160'] = '&nbsp;';
                entities['161'] = '&iexcl;';
                entities['162'] = '&cent;';
                entities['163'] = '&pound;';
                entities['164'] = '&curren;';
                entities['165'] = '&yen;';
                entities['166'] = '&brvbar;';
                entities['167'] = '&sect;';
                entities['168'] = '&uml;';
                entities['169'] = '&copy;';
                entities['170'] = '&ordf;';
                entities['171'] = '&laquo;';
                entities['172'] = '&not;';
                entities['173'] = '&shy;';
                entities['174'] = '&reg;';
                entities['175'] = '&macr;';
                entities['176'] = '&deg;';
                entities['177'] = '&plusmn;';
                entities['178'] = '&sup2;';
                entities['179'] = '&sup3;';
                entities['180'] = '&acute;';
                entities['181'] = '&micro;';
                entities['182'] = '&para;';
                entities['183'] = '&middot;';
                entities['184'] = '&cedil;';
                entities['185'] = '&sup1;';
                entities['186'] = '&ordm;';
                entities['187'] = '&raquo;';
                entities['188'] = '&frac14;';
                entities['189'] = '&frac12;';
                entities['190'] = '&frac34;';
                entities['191'] = '&iquest;';
                entities['192'] = '&Agrave;';
                entities['193'] = '&Aacute;';
                entities['194'] = '&Acirc;';
                entities['195'] = '&Atilde;';
                entities['196'] = '&Auml;';
                entities['197'] = '&Aring;';
                entities['198'] = '&AElig;';
                entities['199'] = '&Ccedil;';
                entities['200'] = '&Egrave;';
                entities['201'] = '&Eacute;';
                entities['202'] = '&Ecirc;';
                entities['203'] = '&Euml;';
                entities['204'] = '&Igrave;';
                entities['205'] = '&Iacute;';
                entities['206'] = '&Icirc;';
                entities['207'] = '&Iuml;';
                entities['208'] = '&ETH;';
                entities['209'] = '&Ntilde;';
                entities['210'] = '&Ograve;';
                entities['211'] = '&Oacute;';
                entities['212'] = '&Ocirc;';
                entities['213'] = '&Otilde;';
                entities['214'] = '&Ouml;';
                entities['215'] = '&times;';
                entities['216'] = '&Oslash;';
                entities['217'] = '&Ugrave;';
                entities['218'] = '&Uacute;';
                entities['219'] = '&Ucirc;';
                entities['220'] = '&Uuml;';
                entities['221'] = '&Yacute;';
                entities['222'] = '&THORN;';
                entities['223'] = '&szlig;';
                entities['224'] = '&agrave;';
                entities['225'] = '&aacute;';
                entities['226'] = '&acirc;';
                entities['227'] = '&atilde;';
                entities['228'] = '&auml;';
                entities['229'] = '&aring;';
                entities['230'] = '&aelig;';
                entities['231'] = '&ccedil;';
                entities['232'] = '&egrave;';
                entities['233'] = '&eacute;';
                entities['234'] = '&ecirc;';
                entities['235'] = '&euml;';
                entities['236'] = '&igrave;';
                entities['237'] = '&iacute;';
                entities['238'] = '&icirc;';
                entities['239'] = '&iuml;';
                entities['240'] = '&eth;';
                entities['241'] = '&ntilde;';
                entities['242'] = '&ograve;';
                entities['243'] = '&oacute;';
                entities['244'] = '&ocirc;';
                entities['245'] = '&otilde;';
                entities['246'] = '&ouml;';
                entities['247'] = '&divide;';
                entities['248'] = '&oslash;';
                entities['249'] = '&ugrave;';
                entities['250'] = '&uacute;';
                entities['251'] = '&ucirc;';
                entities['252'] = '&uuml;';
                entities['253'] = '&yacute;';
                entities['254'] = '&thorn;';
                entities['255'] = '&yuml;';
            }

            if (useQuoteStyle !== 'ENT_NOQUOTES') {
                entities['34'] = '&quot;';
            }
            if (useQuoteStyle === 'ENT_QUOTES') {
                entities['39'] = '&#39;';
            }
            entities['60'] = '&lt;';
            entities['62'] = '&gt;';


            // ascii decimals to real symbols
            for (decimal in entities) {
                symbol = String.fromCharCode(decimal);
                hash_map[symbol] = entities[decimal];
            }

            return hash_map;
        },
        keyCodeToChar: function(keyCode) {
            var cResult = null;

            switch (keyCode) {
                case 96:
                    cResult = "0";
                    break;
                case 97:
                    cResult = "1";
                    break;
                case 98:
                    cResult = "2";
                    break;
                case 99:
                    cResult = "3";
                    break;
                case 100:
                    cResult = "4";
                    break;
                case 101:
                    cResult = "5";
                    break;
                case 102:
                    cResult = "6";
                    break;
                case 103:
                    cResult = "7";
                    break;
                case 104:
                    cResult = "8";
                    break;
                case 105:
                    cResult = "9";
                    break;
                case 110:
                    cResult = ".";
                    break;
                case 186:
                    cResult = ";";
                    break;
                case 187:
                    cResult = "=";
                    break;
                case 188:
                    cResult = "\'";
                    break;
                case 189:
                    cResult = "-";
                    break;
                case 190:
                    cResult = ".";
                    break;
                case 191:
                    cResult = "/";
                    break;
                case 192:
                    cResult = "`";
                    break;
                case 219:
                    cResult = "[";
                    break;
                case 220:
                    cResult = "\\";
                    break;
                case 221:
                    cResult = "]";
                    break;
                case 222:
                    cResult = "'";
                    break;
                default:
                    cResult = String.fromCharCode(keyCode);
            }

            return cResult;
        }
    },
    text: {
        truncate: function(text, length, appendText) {
            if (appendText === undefined) {
                appendText = "";
            }
            if (text && text.length > length) {
                var iIndex = text.lastIndexOf(" ", length);
                if (iIndex === -1) {
                    text = text.substring(0, length);
                } else {
                    text = text.substring(0, iIndex);
                }
                text = text + appendText;
            }
            return text;
        },
        trim: function(text) {
            if (text) {
                text = text.replace(/^\s+|\s+$/g, "");
            }
            return text;
        },
        ltrim: function(text) {
            if (text) {
                text = text.replace(/^\s+/, "");
            }
            return text;
        },
        rtrim: function(text) {
            if (text) {
                text = text.replace(/\s+$/, "");
            }
            return text;
        },
        startsWith: function(text, find) {
            return text.slice(0, find.length) == find;
        },
        endsWith: function(text, find) {
            return text.slice(-find.length) == find;
        }
    },
    query: {
        where: function(aObj, fnCallBackWhere) {
            var aResult = [];
            var iIndex;
            for (iIndex = 0; iIndex < aObj.length; iIndex++) {
                if (fnCallBackWhere(aObj[iIndex])) {
                    aResult.push(aObj[iIndex]);
                }
            }
            return aResult;
        },
        orderby: function(aObj, fnCallBackSort) {
            var aResult = [];
            var iIndex;
            for (iIndex = 0; iIndex < aObj.length; iIndex++) {
                aResult.push(aObj[iIndex]);
            }
            this.bubbleSort(aResult, fnCallBackSort);

            return aResult;
        },
        partition: function(aObj, fnCallBackSort, iBegin, iEnd, iPivot) {
            var oInter;
            var iIndex;
            var iStore = iBegin;

            oInter = aObj[iPivot];
            aObj[iPivot] = aObj[iEnd - 1];
            aObj[iEnd - 1] = oInter;
            for (iIndex = iBegin; iIndex < iEnd - 1; iIndex++) {
                if (fnCallBackSort(aObj[iIndex], aObj[iPivot]) <= 0) {
                    oInter = aObj[iStore];
                    aObj[iStore] = aObj[iIndex];
                    aObj[iIndex] = oInter;
                    iStore++;
                }
            }
            oInter = aObj[iStore];
            aObj[iStore] = aObj[iEnd - 1];
            aObj[iEnd - 1] = oInter;
            return iStore;
        },
        qsort: function(aObj, fnCallBackSort, iBegin, iEnd) {
            if (iBegin == undefined) {
                iBegin = 0;
            }
            if (iEnd == undefined) {
                iEnd = aObj.length;
            }
            if (iEnd - 1 > iBegin) {
                //var iPivot = iBegin + Math.floor(Math.random() * (iEnd - iBegin - 1));
                var iPivot = iBegin;
                iPivot = this.partition(aObj, fnCallBackSort, iBegin, iEnd, iPivot);
                this.qsort(aObj, fnCallBackSort, iBegin, iPivot);
                this.qsort(aObj, fnCallBackSort, iPivot + 1, iEnd);
            }
        },
        bubbleSort: function(aObj, fnCallBackSort) {
            var oInter;
            for (var iIndex = 0; iIndex < aObj.length; iIndex++) {
                for (var jIndex = iIndex + 1; jIndex < aObj.length; jIndex++) {
                    if (fnCallBackSort(aObj[iIndex], aObj[jIndex]) > 0) {
                        oInter = aObj[jIndex];
                        aObj[jIndex] = aObj[iIndex];
                        aObj[iIndex] = oInter;
                    }
                }
            }
        },
        sum: function(aObj, fnCallBackSum) {
            var nTotal = 0;
            var iIndex = 0;
            for (iIndex = 0; iIndex < aObj.length; iIndex++) {
                var nSubTotal = fnCallBackSum(aObj[iIndex]);
                if (nSubTotal !== null && nSubTotal !== undefined) {
                    nTotal += nSubTotal;
                }
            }
            return nTotal;
        },
        any: function(aObj, fnCallBackAny) {
            var iIndex = 0;
            for (iIndex = 0; iIndex < aObj.length; iIndex++) {
                if (fnCallBackAny(aObj[iIndex])) {
                    return true;
                }
            }
            return false;
        },
        take: function(aObj, iFrom, iSize) {
            var aResult = [];
            var iIndex = 0;

            if (iSize === undefined) {
                iSize = iFrom;
                iFrom = 0;
            }

            for (iIndex = iFrom; iIndex < aObj.length && aResult.length < iSize; iIndex++) {
                aResult.push(aObj[iIndex]);
            }
            return aResult;
        },
        select: function(aObj, fnCallBackSelect) {
            var aResult = [];
            var iIndex;
            for (iIndex = 0; iIndex < aObj.length; iIndex++) {
                aResult.push(fnCallBackSelect(aObj[iIndex], iIndex));
            }
            return aResult;
        }
    },
    fecha: {
        getMesNumero: function(param) {

            var iNumeroMes = 0;
            var tipoParam = typeof (param);

            switch (tipoParam) {
                case 'string':
                    iNumeroMes = this.getNumeroMes(param);
                    break;
                case 'number':
                    iNumeroMes = this.getNumeroMesPosicion(param);
                    break;
            }

            return iNumeroMes;
        },
        getNumeroMes: function() {

        },
        getNumeroMesPosicion: function(iNumeroArray) {
            iNumeroArray = parseInt(iNumeroArray);
            if (isNaN(iNumeroArray)) {
                return;
            }

            iNumeroArray = iNumeroArray + 1;
            if (iNumeroArray > 12) {
                return;
            }

            var cNumero = iNumeroArray.toString();
            if (iNumeroArray < 10) {
                cNumero = "0" + cNumero;
            }
            return cNumero;
        },
        /**
     * Metodo que se encarga de sumar los dias para calcular la fecha fin
     * @returns {Boolean|undefined}
     */
      sumarDias: function sumarDias(dtFechaInicial, iDias, cOperacion) {
            var aFecha = dtFechaInicial.split("-");
            var oFecha = new Date(aFecha[0], aFecha[1], aFecha[2]);

            switch(cOperacion)
            {
                case "-":
                    oFecha.setDate(oFecha.getDate() + (iDias - 1))
                    break;
                case "+":
                    oFecha.setDate(oFecha.getDate() + (iDias + 1))
                    break;
                default:
                    oFecha.setDate(oFecha.getDate() + iDias)
                    break;
            }

            var dtFechaFinal = oFecha.getFullYear().toString() + "-" + ((oFecha.getMonth().toString().length == 1) ? "0" + oFecha.getMonth().toString() : oFecha.getMonth().toString()) + "-" + ((oFecha.getDate().toString().length == 1) ? "0" + oFecha.getDate().toString() : oFecha.getDate().toString());
            return dtFechaFinal;
        }
    },
    str: {
        sprintf: function sprintf() {

            var regex = /%%|%(\d+\$)?([-+\'#0 ]*)(\*\d+\$|\*|\d+)?(\.(\*\d+\$|\*|\d+))?([scboxXuideEfFgG])/g;
            var a = arguments;
            var i = 0;
            var format = a[i++];

            // pad()
            var pad = function(str, len, chr, leftJustify) {
                if (!chr) {
                    chr = ' ';
                }
                var padding = (str.length >= len) ? '' : new Array(1 + len - str.length >>> 0)
                        .join(chr);
                return leftJustify ? str + padding : padding + str;
            };

            // justify()
            var justify = function(value, prefix, leftJustify, minWidth, zeroPad, customPadChar) {
                var diff = minWidth - value.length;
                if (diff > 0) {
                    if (leftJustify || !zeroPad) {
                        value = pad(value, minWidth, customPadChar, leftJustify);
                    } else {
                        value = value.slice(0, prefix.length) + pad('', diff, '0', true) + value.slice(prefix.length);
                    }
                }
                return value;
            };

            // formatBaseX()
            var formatBaseX = function(value, base, prefix, leftJustify, minWidth, precision, zeroPad) {
                // Note: casts negative numbers to positive ones
                var number = value >>> 0;
                prefix = prefix && number && {
                    '2': '0b',
                    '8': '0',
                    '16': '0x'
                }[base] || '';
                value = prefix + pad(number.toString(base), precision || 0, '0', false);
                return justify(value, prefix, leftJustify, minWidth, zeroPad);
            };

            // formatString()
            var formatString = function(value, leftJustify, minWidth, precision, zeroPad, customPadChar) {
                if (precision != null) {
                    value = value.slice(0, precision);
                }
                return justify(value, '', leftJustify, minWidth, zeroPad, customPadChar);
            };

            // doFormat()
            var doFormat = function(substring, valueIndex, flags, minWidth, _, precision, type) {
                var number, prefix, method, textTransform, value;

                if (substring === '%%') {
                    return '%';
                }

                // parse flags
                var leftJustify = false;
                var positivePrefix = '';
                var zeroPad = false;
                var prefixBaseX = false;
                var customPadChar = ' ';
                var flagsl = flags.length;
                for (var j = 0; flags && j < flagsl; j++) {
                    switch (flags.charAt(j)) {
                        case ' ':
                            positivePrefix = ' ';
                            break;
                        case '+':
                            positivePrefix = '+';
                            break;
                        case '-':
                            leftJustify = true;
                            break;
                        case "'":
                            customPadChar = flags.charAt(j + 1);
                            break;
                        case '0':
                            zeroPad = true;
                            customPadChar = '0';
                            break;
                        case '#':
                            prefixBaseX = true;
                            break;
                    }
                }

                // parameters may be null, undefined, empty-string or real valued
                // we want to ignore null, undefined and empty-string values
                if (!minWidth) {
                    minWidth = 0;
                } else if (minWidth === '*') {
                    minWidth = +a[i++];
                } else if (minWidth.charAt(0) == '*') {
                    minWidth = +a[minWidth.slice(1, -1)];
                } else {
                    minWidth = +minWidth;
                }

                // Note: undocumented perl feature:
                if (minWidth < 0) {
                    minWidth = -minWidth;
                    leftJustify = true;
                }

                if (!isFinite(minWidth)) {
                    throw new Error('sprintf: (minimum-)width must be finite');
                }

                if (!precision) {
                    precision = 'fFeE'.indexOf(type) > -1 ? 6 : (type === 'd') ? 0 : undefined;
                } else if (precision === '*') {
                    precision = +a[i++];
                } else if (precision.charAt(0) == '*') {
                    precision = +a[precision.slice(1, -1)];
                } else {
                    precision = +precision;
                }

                // grab value using valueIndex if required?
                value = valueIndex ? a[valueIndex.slice(0, -1)] : a[i++];

                switch (type) {
                    case 's':
                        return formatString(String(value), leftJustify, minWidth, precision, zeroPad, customPadChar);
                    case 'c':
                        return formatString(String.fromCharCode(+value), leftJustify, minWidth, precision, zeroPad);
                    case 'b':
                        return formatBaseX(value, 2, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
                    case 'o':
                        return formatBaseX(value, 8, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
                    case 'x':
                        return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
                    case 'X':
                        return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad)
                                .toUpperCase();
                    case 'u':
                        return formatBaseX(value, 10, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
                    case 'i':
                    case 'd':
                        number = +value || 0;
                        number = Math.round(number - number % 1); // Plain Math.round doesn't just truncate
                        prefix = number < 0 ? '-' : positivePrefix;
                        value = prefix + pad(String(Math.abs(number)), precision, '0', false);
                        return justify(value, prefix, leftJustify, minWidth, zeroPad);
                    case 'e':
                    case 'E':
                    case 'f': // Should handle locales (as per setlocale)
                    case 'F':
                    case 'g':
                    case 'G':
                        number = +value;
                        prefix = number < 0 ? '-' : positivePrefix;
                        method = ['toExponential', 'toFixed', 'toPrecision']['efg'.indexOf(type.toLowerCase())];
                        textTransform = ['toString', 'toUpperCase']['eEfFgG'.indexOf(type) % 2];
                        value = prefix + Math.abs(number)[method](precision);
                        return justify(value, prefix, leftJustify, minWidth, zeroPad)[textTransform]();
                    default:
                        return substring;
                }
            };

            return format.replace(regex, doFormat);
        },
        ucwords: function(str) {
            return (str + '')
                    .replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function($1) {
                        return $1.toUpperCase();
                    });
        }
    }
};

