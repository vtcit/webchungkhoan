[
	branding: false,
	//'menu: [insert: ['title: 'Insert', 'items: 'readmore image']],
	external_menus: [insert: 'readmore'],
	content_css: Url::to('@baseurl/admin/css/custom-tinymce-editor.css?v=1'),
	automatic_uploads: true,
	images_upload_url: Url::to(['media/upload', 'json: true]),
	//'images_upload_base_path: '/some/basepath',
	images_upload_credentials: true,
	icons_url: Url::to('@baseurl/myTinymce/icons/readmore/icons.js'),
	icons: 'readmore',      // use icon pack
	external_plugins: [
		readmore: Url::to('@baseurl/myTinymce/plugins/readmore/plugin.js?v=4'),
		youtube: Url::to('@baseurl/myTinymce/plugins/youtube/plugin.min.js')
	],
	plugins: [
		"advlist autolink lists link charmap anchor",
		"searchreplace visualblocks code fullscreen",
		"media table contextmenu paste image hr emoticons" //imagetools
	], // strikethrough alignjustify forecolor backcolor
	toolbar: [
		formatselect  bold italic underline superscript subscript  alignleft aligncenter alignright  bullist numlist  blockquote link unlink | fullscreen',
		outdent indent | pastetext removeformat searchreplace | table media image hr readmore charmap emoticons youtube | undo redo code'
	],
	images_upload_handler: function (blobInfo, success, failure) {
		alert('images_upload_handler');
	}
]