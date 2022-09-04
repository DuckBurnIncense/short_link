const SPEC_CHARS = ['#', '/', ' ', '\\', '%', '?'];
const isURL = u => /^((https?:)?\/\/)?(?:www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()!@:%_\+.~#?&\/\/=]*)$/
.test(u);
const isStringHasSpecialChars = s => {
	let r = false;
	SPEC_CHARS.forEach(c => {
		if (s.includes(c)) r = true;
	});
	return r;
}
export {
	SPEC_CHARS,
	isURL,
	isStringHasSpecialChars,
}