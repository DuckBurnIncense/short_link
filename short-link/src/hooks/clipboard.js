export function useCopy(t) {
	const input = document.createElement('input');
	document.body.appendChild(input);
	input.setAttribute('value', t);
	input.select();
	document.execCommand('copy')
	document.body.removeChild(input);
}