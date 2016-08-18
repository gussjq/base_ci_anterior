(function( factory ) {
	if ( typeof define === "function" && define.amd ) {
		define( ["jquery", "../jquery.validate"], factory );
	} else {
		factory( jQuery );
	}
}(function( $ ) {

/*
 * Translated default messages for the jQuery validation plugin.
 * Locale: ES (Spanish; Español)
 */
$.extend($.validator.messages, {
	required: Generic.VALIDADOR_TEXT_REQUIRED,
	remote: Generic.VALIDADOR_TEXT_REMOTE,
	email: Generic.VALIDADOR_TEXT_EMAIL,
	url: Generic.VALIDADOR_TEXT_URL,
	date: Generic.VALIDADOR_TEXT_DATE,
	dateISO: Generic.VALIDADOR_TEXT_DATE_ISO,
	number: Generic.VALIDADOR_TEXT_NUMBER,
	digits: Generic.VALIDADOR_TEXT_DIGITS,
	creditcard: Generic.VALIDADOR_TEXT_CREDIT_CARD,
	equalTo: Generic.VALIDADOR_TEXT_EQUAL_TO,
	extension: Generic.VALIDADOR_TEXT_EXTENSION,
	maxlength: $.validator.format(Generic.VALIDADOR_TEXT_MAXLENGTH),
	minlength: $.validator.format(Generic.VALIDADOR_TEXT_MINLENGTH),
	rangelength: $.validator.format(Generic.VALIDADOR_TEXT_RANGELENGTH),
	range: $.validator.format(Generic.VALIDADOR_TEXT_RANGE),
	max: $.validator.format(Generic.VALIDADOR_TEXT_MAX),
	min: $.validator.format(Generic.VALIDADOR_TEXT_MIN),
	nifES: Generic.VALIDADOR_TEXT_NIF_ES,
	nieES: Generic.VALIDADOR_TEXT_NIE_ES,
	cifES: Generic.VALIDADOR_TEXT_CIF_ES
});

}));