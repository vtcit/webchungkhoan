
(function () {
    'use strict';

    var global = tinymce.util.Tools.resolve('tinymce.PluginManager');

    var global$1 = tinymce.util.Tools.resolve('tinymce.Env');

    var getSeparatorHtml = function (editor) {
      return editor.getParam('readmore_separator', '<!-- readmore -->');
    };
    var shouldSplitBlock = function (editor) {
      return editor.getParam('readmore_split_block', false);
    };
    var Settings = {
      getSeparatorHtml: getSeparatorHtml,
      shouldSplitBlock: shouldSplitBlock
    };

    var getReadmoreClass = function () {
      return 'editor-readmore';
    };
    var getPlaceholderHtml = function () {
      return '<img src="' + global$1.transparentSrc + '" class="' + getReadmoreClass() + '" data-readmore data-mce-resize="false" data-mce-placeholder />';
    };
    var setup = function (editor) {
      var separatorHtml = Settings.getSeparatorHtml(editor);
      var readmoreSeparatorRegExp = new RegExp(separatorHtml.replace(/[\?\.\*\[\]\(\)\{\}\+\^\$\:]/g, function (a) {
        return '\\' + a;
      }), 'gi');
      editor.on('BeforeSetContent', function (e) {
        e.content = e.content.replace(readmoreSeparatorRegExp, getPlaceholderHtml());
      });
      editor.on('PreInit', function () {
        editor.serializer.addNodeFilter('img', function (nodes) {
          var i = nodes.length, node, className;
          while (i--) {
            node = nodes[i];
            className = node.attr('class');
            if (className && className.indexOf('editor-readmore') !== -1) {
              var parentNode = node.parent;
              if (editor.schema.getBlockElements()[parentNode.name] && Settings.shouldSplitBlock(editor)) {
                parentNode.type = 3;
                parentNode.value = separatorHtml;
                parentNode.raw = true;
                node.remove();
                continue;
              }
              node.type = 3;
              node.value = separatorHtml;
              node.raw = true;
            }
          }
        });
      });
    };
    var FilterContent = {
      setup: setup,
      getPlaceholderHtml: getPlaceholderHtml,
      getReadmoreClass: getReadmoreClass
    };

    var register = function (editor) {
      editor.addCommand('Readmore', function () {
        if (editor.settings.readmore_split_block) {
          editor.insertContent('<p>' + FilterContent.getPlaceholderHtml() + '</p>');
        } else {
          editor.insertContent(FilterContent.getPlaceholderHtml());
        }
      });
    };
    var Commands = { register: register };

    var setup$1 = function (editor) {
      editor.on('ResolveName', function (e) {
        if (e.target.nodeName === 'IMG' && editor.dom.hasClass(e.target, FilterContent.getReadmoreClass())) {
          e.name = 'readmore';
        }
      });
    };
    var ResolveName = { setup: setup$1 };

    var register$1 = function (editor) {
      editor.ui.registry.addButton('readmore', {
        icon: 'readmore',
        tooltip: 'Readmore',
        onAction: function () {
          return editor.execCommand('Readmore');
        }
      });
      editor.ui.registry.addButton('insert_media', {
        icon: 'media',
		text: '+Media',
        tooltip: 'media',
        onAction: function () {
          $('#insertMediaBtn').click();
        }
      });
      editor.ui.registry.addMenuItem('readmore', {
        text: 'Readmore',
        icon: 'readmore',
		context: 'insert',
        tooltip: 'Readmore',
        onAction: function () {
          return editor.execCommand('Readmore');
        }
      });
    };
    var Buttons = { register: register$1 };

    function Plugin () {
      global.add('readmore', function (editor) {
        Commands.register(editor);
        Buttons.register(editor);
        FilterContent.setup(editor);
        ResolveName.setup(editor);
      });
    }

    Plugin();

}());
