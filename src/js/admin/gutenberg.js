/*
|--------------------------------------------------------------------------
| CUSTOMIZE GUTENBERG
|--------------------------------------------------------------------------
*/

wp.domReady(function() {
	// remove discussion panel
	const { removeEditorPanel } = wp.data.dispatch('core/edit-post');
	removeEditorPanel('discussion-panel');
});
