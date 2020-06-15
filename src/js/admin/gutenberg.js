//	/**
// @ CUSTOMIZAÇÃO DO GUTENBERG
//	*/

wp.domReady(function() {
	// remover painel de discussão
	const { removeEditorPanel } = wp.data.dispatch('core/edit-post');
	removeEditorPanel('discussion-panel');
});
