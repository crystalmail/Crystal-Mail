cmail.contextmenu_command_handlers = new Object();
cmail.contextmenu_disable_multi = new Array('#reply','#reply-all','#forward','#print','#edit','#viewsource','#download','#open','#edit');

function rcm_contextmenu_update() {
	if (cmail.env.trash_mailbox && cmail.env.mailbox != cmail.env.trash_mailbox)
		$("#rcm_delete").html(cmail.gettext('movemessagetotrash'));
	else
		$("#rcm_delete").html(cmail.gettext('deletemessage'));
}

function rcm_contextmenu_init(row) {
	$("#" + row).contextMenu({
		menu: 'rcmContextMenu',
		submenu_delay: 400
	},
	function(command, el, pos) {
		if ($(el) && String($(el).attr('id')).match(/rcmrow([a-z0-9\-_=]+)/i))
		{
			var prev_uid = cmail.env.uid;
			if (cmail.message_list.selection.length <= 1)
				cmail.env.uid = RegExp.$1;

			// fix command string in IE
			if (command.indexOf("#") > 0)
				command = command.substr(command.indexOf("#") + 1);

			// enable the required command
			cmd = (command == 'read' || command == 'unread' || command == 'flagged' || command == 'unflagged') ? 'mark' : command;
			var prev_command = cmail.commands[cmd];
			cmail.enable_command(cmd, true);

			// process external commands
			if (typeof cmail.contextmenu_command_handlers[command] == 'function')
				cmail.contextmenu_command_handlers[command](command, el, pos);
			else if (typeof cmail.contextmenu_command_handlers[command] == 'string')
				window[cmail.contextmenu_command_handlers[command]](command, el, pos);
			else
			{
				switch (command)
				{
				case 'read':
				case 'unread':
				case 'flagged':
				case 'unflagged':
					cmail.command('mark', command, $(el));
					break;
				case 'reply':
				case 'reply-all':
				case 'forward':
				case 'print':
				case 'download':
				case 'edit':
				case 'viewsource':
					cmail.command(command, '', $(el));
					break;
				case 'open':
					cmail.command(command, '', crystal_find_object('rcm_open'));
					cmail.sourcewin = window.open(crystal_find_object('rcm_open').href);
					if (cmail.sourcewin)
						window.setTimeout(function(){ cmail.sourcewin.focus(); }, 20);

					crystal_find_object('rcm_open').href = '#open';
					break;
				case 'delete':
					if (cmail.message_list.selection.length > 1 || cmail.env.uid == cmail.message_list.get_selection()) {
						cmail.env.uid = null;
						cmail.command(command, '', $(el));
					}
					else {
						var prev_contentframe = cmail.env.contentframe;
						var prev_selection = cmail.message_list.get_selection();
						cmail.env.contentframe = false;
						cmail.message_list.select(cmail.env.uid);
						cmail.env.uid = null;
						cmail.command(command, '', $(el));

						if (prev_selection != '')
							cmail.message_list.select(prev_selection);
						else
							cmail.message_list.clear_selection();

						cmail.env.contentframe = prev_contentframe;
					}
					break;
				case 'moveto':
					if (cmail.env.rcm_destfolder == cmail.env.mailbox)
						return;

					cmail.command(command, cmail.env.rcm_destfolder, $(el));

					if (cmail.message_list.selection.length <= 1)
						cmail.message_list.remove_row(cmail.env.uid, false);

					cmail.env.rcm_destfolder = null;
					break;
				}
			}

			cmail.enable_command(cmd, prev_command);
			cmail.env.uid = prev_uid;
		}
	});
}

function rcm_selection_changed(id, list) {
	if (list.selection.length > 1)
		$('#' + id).disableContextMenuItems(cmail.contextmenu_disable_multi.join(','));
	else
		$('#' + id).enableContextMenuItems(cmail.contextmenu_disable_multi.join(','));
}

function rcm_set_dest_folder(folder) {
	cmail.env.rcm_destfolder = folder;
}

function rcm_contextmenu_register_command(command, callback, label, pos, sep, multi, newSub, menu) {
	if (!menu)
		menu = $('#rcmContextMenu');

	var menuItem = $('<li>').addClass(command);
	$('<a>').attr('href', '#' + command).addClass('active').html(cmail.gettext(label)).appendTo(menuItem);
	cmail.contextmenu_command_handlers[command] = callback;

	if (pos && $('#rcmContextMenu .' + pos) && newSub) {
		subMenu = $('#rcmContextMenu .' + pos);
		subMenu.addClass('submenu');

		// remove any existing hyperlink
		if (subMenu.children('a')) {
			var text = subMenu.children('a').html();
			subMenu.html(text);
		}

		var newMenu = $('<ul>').addClass('toolbarmenu').appendTo(subMenu);
		newMenu.append(menuItem);
	}
	else if (pos && $('#rcmContextMenu .' + pos))
		$('#rcmContextMenu .' + pos).before(menuItem);
	else
		menu.append(menu);

	if (sep == 'before')
		menuItem.addClass('separator_above');
	else if (sep == 'after')
		menuItem.addClass('separator_below');

	if (!multi)
		cmail.contextmenu_disable_multi[cmail.contextmenu_disable_multi.length] = '#' + command;
}

function rcm_foldermenu_init() {
	$("#mailboxlist-container li").contextMenu({
		menu: 'rcmFolderMenu'
	},
	function(command, el, pos) {
		var matches = String($(el).children('a').attr('onclick')).match(/.*cmail.command\(["']list["'],\s*["']([^"']*)["'],\s*this\).*/i);
		if ($(el) && matches)
		{
			var mailbox = matches[1];
			var messagecount = 0;

			if (command == 'readfolder' || command == 'expunge' || command == 'purge') {
				if (mailbox == cmail.env.mailbox) {
					messagecount = cmail.env.messagecount;
				}
				else if (cmail.env.unread_counts[mailbox] == 0) {
					cmail.set_busy(true, 'loading');

					querystring = '_mbox=' + urlencode(mailbox);
				    querystring += (querystring ? '&' : '') + '_remote=1';
				    var url = cmail.env.comm_path + '&_action=' + 'plugin.contextmenu.messagecount' + '&' + querystring

				    // send request
				    console.log('HTTP POST: ' + url);

				    jQuery.ajax({
				         url:    url,
				         dataType: "json",
				         success: function(response){ messagecount = response.env.messagecount; },
				         async:   false
				    });

				    cmail.set_busy(false);
				}

				if (cmail.env.unread_counts[mailbox] == 0 && messagecount == 0) {
					cmail.display_message(cmail.get_label('nomessagesfound'), 'notice');
					return false;
				}
			}

			// fix command string in IE
			if (command.indexOf("#") > 0)
				command = command.substr(command.indexOf("#") + 1);

			// enable the required command
			var prev_command = cmail.commands[command];
			cmail.enable_command(command, true);

			// process external commands
			if (typeof cmail.contextmenu_command_handlers[command] == 'function')
				cmail.contextmenu_command_handlers[command](command, el, pos);
			else if (typeof cmail.contextmenu_command_handlers[command] == 'string')
				window[cmail.contextmenu_command_handlers[command]](command, el, pos);
			else
			{
				switch (command)
				{
					case 'readfolder':
						cmail.set_busy(true, 'loading');
						cmail.http_request('plugin.contextmenu.readfolder', '_mbox=' + urlencode(mailbox), true);

						if (mailbox == cmail.env.mailbox) {
							for (var i in cmail.env.messages) {
								if (cmail.env.messages[i].unread)
									cmail.set_message(i, 'unread', false);
							}
						}
						break;
					case 'expunge':
						cmail.expunge_mailbox(mailbox);
						break;
					case 'purge':
						cmail.purge_mailbox(mailbox);
						break;
					case 'collapseall':
						$("#mailboxlist div.expanded").each( function() {
							var el = $(this);
							var matches = String($(el).attr('onclick')).match(/.*cmail.command\(["']collapse-folder["'],\s*["']([^"']*)["']\).*/i);
							cmail.collapse_folder(matches[1]);
						});
						break;
					case 'expandall':
						$("#mailboxlist div.collapsed").each( function() {
							var el = $(this);
							var matches = String($(el).attr('onclick')).match(/.*cmail.command\(["']collapse-folder["'],\s*["']([^"']*)["']\).*/i);
							cmail.collapse_folder(matches[1]);
						});
						break;
					case 'openfolder':
						crystal_find_object('rcm_openfolder').href = '?_task=mail&_mbox='+urlencode(mailbox);
						cmail.sourcewin = window.open(crystal_find_object('rcm_openfolder').href);
						if (cmail.sourcewin)
							window.setTimeout(function(){ cmail.sourcewin.focus(); }, 20);

						crystal_find_object('rcm_openfolder').href = '#openfolder';
						break;
				}
			}

			cmail.enable_command(command, prev_command);
		}
	});
}

function rcm_folder_options(el) {
	$('#rcmFolderMenu').disableContextMenuItems('#readfolder,#purge,#collapseall,#expandall');

	var matches = String($(el).children('a').attr('onclick')).match(/.*cmail.command\(["']list["'],\s*["']([^"']*)["'],\s*this\).*/i);
	if ($(el) && matches)
	{
		var mailbox = matches[1];

		if (cmail.env.unread_counts[mailbox] > 0)
			$('#rcmFolderMenu').enableContextMenuItems('#readfolder');

		if (mailbox == cmail.env.trash_mailbox || mailbox == cmail.env.junk_mailbox
			|| mailbox.match('^' + RegExp.escape(cmail.env.trash_mailbox) + RegExp.escape(cmail.env.delimiter))
			|| mailbox.match('^' + RegExp.escape(cmail.env.junk_mailbox) + RegExp.escape(cmail.env.delimiter)))
				$('#rcmFolderMenu').enableContextMenuItems('#purge');

		if ($("#mailboxlist div.expanded").length > 0)
			$('#rcmFolderMenu').enableContextMenuItems('#collapseall');

		if ($("#mailboxlist div.collapsed").length > 0)
			$('#rcmFolderMenu').enableContextMenuItems('#expandall');
	}
}

function rcm_addressmenu_init(row) {
	$("#" + row).contextMenu({
		menu: 'rcmAddressMenu'
	},
	function(command, el, pos) {
		if ($(el) && String($(el).attr('id')).match(/rcmrow([a-z0-9\-_=]+)/i))
		{
			var prev_cid = cmail.env.cid;
			if (cmail.contact_list.selection.length <= 1)
				cmail.env.cid = RegExp.$1;

			// fix command string in IE
			if (command.indexOf("#") > 0)
				command = command.substr(command.indexOf("#") + 1);

			// enable the required command
			cmd = command;
			var prev_command = cmail.commands[cmd];
			cmail.enable_command(cmd, true);

			// process external commands
			if (typeof cmail.contextmenu_command_handlers[command] == 'function')
				cmail.contextmenu_command_handlers[command](command, el, pos);
			else if (typeof cmail.contextmenu_command_handlers[command] == 'string')
				window[cmail.contextmenu_command_handlers[command]](command, el, pos);
			else
			{
				switch (command)
				{
				case 'edit':
					cmail.command(command, '', $(el));
					var prev_contentframe = cmail.env.contentframe;
					cmail.env.contentframe = false;
					cmail.contact_list.highlight_row(cmail.env.cid);
					cmail.env.contentframe = prev_contentframe;
					break;
				case 'compose':
				case 'delete':
					if (cmail.contact_list.selection.length > 1 || cmail.env.cid == cmail.contact_list.get_selection()) {
						cmail.env.cid = null;
						cmail.command(command, '', $(el));
					}
					else {
						var prev_contentframe = cmail.env.contentframe;
						var prev_selection = cmail.contact_list.get_selection();
						cmail.env.contentframe = false;
						cmail.contact_list.select(cmail.env.cid);
						cmail.env.cid = null;
						cmail.command(command, '', $(el));

						if (prev_selection != '')
							cmail.contact_list.select(prev_selection);
						else
							cmail.contact_list.clear_selection();

						cmail.env.contentframe = prev_contentframe;
					}
					break;
				case 'moveto':
					if (cmail.env.rcm_destbook == cmail.env.source)
						return;

					if (cmail.contact_list.selection.length > 1 || cmail.env.cid == cmail.contact_list.get_selection()) {
						cmail.env.cid = null;
						cmail.drag_active = true;
						cmail.command(command, cmail.env.rcm_destbook, $(el));
						cmail.drag_active = false;
					}
					else {
						var prev_contentframe = cmail.env.contentframe;
						var prev_selection = cmail.contact_list.get_selection();
						cmail.env.contentframe = false;
						cmail.contact_list.select(cmail.env.cid);
						cmail.env.cid = null;
						cmail.drag_active = true;
						cmail.command(command, cmail.env.rcm_destbook, $(el));
						cmail.drag_active = false;

						if (prev_selection != '')
							cmail.contact_list.select(prev_selection);
						else
							cmail.contact_list.clear_selection();

						cmail.env.contentframe = prev_contentframe;
						cmail.env.rcm_destbook = null;
					}
					break;
				}
			}

			cmail.enable_command(cmd, prev_command);
			cmail.env.cid = prev_cid;
		}
	});
}

function rcm_set_dest_book(book) {
	cmail.env.rcm_destbook = book;
}

$(document).ready(function(){
	// init message list menu
	if ($('#rcmContextMenu').length > 0) {
		cmail.add_onload('if (cmail.message_list) cmail.message_list.addEventListener(\'select\', function(list) { rcm_selection_changed(\'rcmContextMenu\', list); } );');
		cmail.addEventListener('listupdate', function(props) { rcm_contextmenu_update(); rcm_contextmenu_init('messagelist tbody tr'); } );
		cmail.addEventListener('insertrow', function(props) { rcm_contextmenu_init(props.row.id); } );
	}

	// init folder list menu
	if ($('#rcmFolderMenu').length > 0) {
		cmail.add_onload('rcm_foldermenu_init();');
	}

	// init address book menu
	if ($('#rcmAddressMenu').length > 0) {
		cmail.add_onload('if (cmail.contact_list) cmail.contact_list.addEventListener(\'select\', function(list) { rcm_selection_changed(\'rcmAddressMenu\', list); } );');
		cmail.addEventListener('listupdate', function(props) { rcm_addressmenu_init('contacts-table tbody tr'); } );
		cmail.addEventListener('insertrow', function(props) { rcm_addressmenu_init(props.row.id); } );
	}
});