/* ---------------------------------------------------------------
| CUSTOMIZE GUTENBERG
| @TODO: Compile to assets folder
--------------------------------------------------------------- */

wp.domReady(function() {
	// remove discussion panel
	const { removeEditorPanel } = wp.data.dispatch('core/edit-post');
	removeEditorPanel('discussion-panel');

	// disable fullscreen mode by default
	const isFullscreenMode = wp.data.select('core/edit-post').isFeatureActive('fullscreenMode');
	if (isFullscreenMode) {
		wp.data.dispatch('core/edit-post').toggleFeature('fullscreenMode');
	}
});
