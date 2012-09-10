CKEDITOR.editorConfig = function(config) {
	config.language = 'pl';
	
	config.toolbar_News =
  [
	  { name: 'document', items : [ 'Source' ] },
	  { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
	  { name: 'paragraph', items : [ 'NumberedList','BulletedList' ] },
	  { name: 'links', items : [ 'Link','Unlink'] },
  ];
};

