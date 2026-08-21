wp.hooks.addFilter(
	'lunarBlocks.infobox.fieldSource',
	'lunar-wiki/recognized-fields',
	function () {
		return {
			taxonomy: 'wiki_field',
		};
	}
);