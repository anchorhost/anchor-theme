/**
 * Page layout picker in the block editor sidebar.
 */
(function (wp) {
	'use strict';

	if (!wp || !wp.plugins || !wp.editPost) {
		return;
	}

	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var PluginDocumentSettingPanel = wp.editPost.PluginDocumentSettingPanel;
	var SelectControl = wp.components.SelectControl;
	var withSelect = wp.data.withSelect;
	var withDispatch = wp.data.withDispatch;
	var compose = wp.compose.compose;

	var LAYOUTS = [
		{ label: __('Default', 'anchor-theme'), value: 'default' },
		{ label: __('Plans & pricing', 'anchor-theme'), value: 'plans' },
		{ label: __('About', 'anchor-theme'), value: 'about' },
		{ label: __('Security', 'anchor-theme'), value: 'security' },
		{ label: __('Security documentation', 'anchor-theme'), value: 'security-docs' },
		{ label: __('Contact', 'anchor-theme'), value: 'contact' }
	];

	var Panel = compose(
		withSelect(function (select) {
			var meta = select('core/editor').getEditedPostAttribute('meta') || {};
			return { layout: meta._anchor_page_layout || 'default' };
		}),
		withDispatch(function (dispatch) {
			return {
				setLayout: function (value) {
					dispatch('core/editor').editPost({ meta: { _anchor_page_layout: value } });
				}
			};
		})
	)(function (props) {
		return el(
			PluginDocumentSettingPanel,
			{
				name: 'anchor-page-layout',
				title: __('Anchor layout', 'anchor-theme'),
				className: 'anchor-page-layout'
			},
			el(SelectControl, {
				label: __('Page layout', 'anchor-theme'),
				value: props.layout,
				options: LAYOUTS,
				onChange: props.setLayout,
				help: __('Picks which designed layout renders around the editor content.', 'anchor-theme')
			})
		);
	});

	wp.plugins.registerPlugin('anchor-page-layout', { render: Panel, icon: null });
})(window.wp);
