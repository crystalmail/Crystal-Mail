/**
 * RoundCube functions for default skin interface
 */

/**
 * Settings
 */

function crystal_init_settings_tabs()
{
  var tab = '#settingstabdefault';
  if (window.cmail && cmail.env.action)
    tab = '#settingstab' + (cmail.env.action=='preferences' ? 'default' : (cmail.env.action.indexOf('identity')>0 ? 'identities' : cmail.env.action.replace(/\./g, '')));

  $(tab).addClass('tablink-selected');
  $(tab + '> a').removeAttr('onclick').unbind('click').bind('click', function(){return false;});
}

function crystal_init_address_tabs()
{
  var tab = '#addresstabbasic';
  if (window.cmail && cmail.env.contacttab)
    tab = '#addresstab' + cmail.env.contacttab;

  $(tab).addClass('tablink-selected');
}

function crystal_show_advanced(visible)
{
  $('tr.advanced').css('display', (visible ? (bw.ie ? 'block' : 'table-row') : 'none'));
}

/**
 * Mail Composing
 */

function cmail_show_header_form(id)
{
  var link, row, parent, ns, ps;
  
  link = document.getElementById(id + '-link');
  parent = link.parentNode;

  if ((ns = cmail_next_sibling(link)))
    ns.style.display = 'none';
  else if ((ps = cmail_prev_sibling(link)))
    ps.style.display = 'none';
    
  link.style.display = 'none';

  if ((row = document.getElementById('compose-' + id)))
    {
    var div = document.getElementById('compose-div');
    var headers_div = document.getElementById('compose-headers-div');
    row.style.display = (document.all && !window.opera) ? 'block' : 'table-row';
    div.style.top = parseInt(headers_div.offsetHeight, 10) + 'px';
    }

  return false;
}

function cmail_hide_header_form(id)
{
  var row, parent, ns, link, links;

  link = document.getElementById(id + '-link');
  link.style.display = '';
  
  parent = link.parentNode;
  links = parent.getElementsByTagName('a');

  for (var i=0; i<links.length; i++)
    if (links[i].style.display != 'none')
      for (var j=i+1; j<links.length; j++)
	if (links[j].style.display != 'none')
          if ((ns = cmail_next_sibling(links[i]))) {
	    ns.style.display = '';
	    break;
	  }

  document.getElementById('_' + id).value = '';

  if ((row = document.getElementById('compose-' + id)))
    {
    var div = document.getElementById('compose-div');
    var headers_div = document.getElementById('compose-headers-div');
    row.style.display = 'none';
    div.style.top = parseInt(headers_div.offsetHeight, 10) + 'px';
    }

  return false;
}

function cmail_next_sibling(elm)
{
  var ns = elm.nextSibling;
  while (ns && ns.nodeType == 3)
    ns = ns.nextSibling;
  return ns;
}

function cmail_prev_sibling(elm)
{
  var ps = elm.previousSibling;
  while (ps && ps.nodeType == 3)
    ps = ps.previousSibling;
  return ps;
}

function cmail_init_compose_form()
{
  var cc_field = document.getElementById('_cc');
  if (cc_field && cc_field.value!='')
    cmail_show_header_form('cc');

  var bcc_field = document.getElementById('_bcc');
  if (bcc_field && bcc_field.value!='')
    cmail_show_header_form('bcc');

  // prevent from form data loss when pressing ESC key in IE
  if (bw.ie) {
    var form = crystal_find_object('form');
    form.onkeydown = function (e) { if (crystal_event.get_keycode(e) == 27) crystal_event.cancel(e); };
  }

  // fix editor position on some browsers
  var div = document.getElementById('compose-div');
  var headers_div = document.getElementById('compose-headers-div');
  div.style.top = parseInt(headers_div.offsetHeight, 10) + 'px';
}

/**
 * Mailbox view
 */

function crystal_mail_ui()
{
  this.popupmenus = {
    markmenu:'markmessagemenu',
    searchmenu:'searchmenu',
    messagemenu:'messagemenu',
    listmenu:'listmenu',
    dragmessagemenu:'dragmessagemenu',
    groupmenu:'groupoptionsmenu'
  };
  
  var obj;
  for (var k in this.popupmenus) {
    obj = $('#'+this.popupmenus[k])
    if (obj.length)
      this[k] = obj;
  }
}

crystal_mail_ui.prototype = {

show_popupmenu: function(obj, refname, show, above)
{
  if (typeof show == 'undefined')
    show = obj.is(':visible') ? false : true;
  
  var ref = crystal_find_object(refname);
  if (show && ref) {
    var pos = $(ref).offset();
    obj.css({ left:pos.left, top:(pos.top + (above ? -obj.height() : ref.offsetHeight)) });
  }
  
  obj[show?'show':'hide']();
},

show_markmenu: function(show)
{
  this.show_popupmenu(this.markmenu, 'markreadbutton', show);
},

show_messagemenu: function(show)
{
  this.show_popupmenu(this.messagemenu, 'messagemenulink', show);
},

show_groupmenu: function(show)
{
  this.show_popupmenu(this.groupmenu, 'groupactionslink', show, true);
},

show_searchmenu: function(show)
{
  if (typeof show == 'undefined')
    show = this.searchmenu.is(':visible') ? false : true;

  var ref = crystal_find_object('searchmod');
  if (show && ref) {
    var pos = $(ref).offset();
    this.searchmenu.css({ left:pos.left, top:(pos.top + ref.offsetHeight + 2)});
    this.searchmenu.find(":checked").attr('checked', false);

    if (cmail.env.search_mods) {
      var search_mods = cmail.env.search_mods[cmail.env.mailbox] ? cmail.env.search_mods[cmail.env.mailbox] : cmail.env.search_mods['*'];
      for (var n in search_mods)
        $('#s_mod_' + n).attr('checked', true);
    }
  }
  this.searchmenu[show?'show':'hide']();
},
 
set_searchmod: function(elem)
{
  if (!cmail.env.search_mods)
    cmail.env.search_mods = {};
  
  if (!cmail.env.search_mods[cmail.env.mailbox])
    cmail.env.search_mods[cmail.env.mailbox] = crystal_clone_object(cmail.env.search_mods['*']);
  
  if (!elem.checked)
    delete(cmail.env.search_mods[cmail.env.mailbox][elem.value]);
  else
    cmail.env.search_mods[cmail.env.mailbox][elem.value] = elem.value;
},

show_listmenu: function(show)
{
  if (typeof show == 'undefined')
    show = this.listmenu.is(':visible') ? false : true;

  var ref = crystal_find_object('listmenulink');
  if (show && ref) {
    var pos = $(ref).offset();
    this.listmenu.css({ left:pos.left, top:(pos.top + ref.offsetHeight + 2)});
    // set form values
    $('input[name="sort_col"][value="'+cmail.env.sort_col+'"]').attr('checked', 1);
    $('input[name="sort_ord"][value="DESC"]').attr('checked', cmail.env.sort_order=='DESC' ? 1 : 0);
    $('input[name="sort_ord"][value="ASC"]').attr('checked', cmail.env.sort_order=='DESC' ? 0 : 1);
    $('input[name="view"][value="thread"]').attr('checked', cmail.env.threading ? 1 : 0);
    $('input[name="view"][value="list"]').attr('checked', cmail.env.threading ? 0 : 1);
    // list columns
    var cols = $('input[name="list_col[]"]');
    for (var i=0; i<cols.length; i++) {
      var found = 0;
      if (cols[i].value != 'from')
        found = jQuery.inArray(cols[i].value, cmail.env.coltypes) != -1;
      else
        found = (jQuery.inArray('from', cmail.env.coltypes) != -1
	    || jQuery.inArray('to', cmail.env.coltypes) != -1);
      $(cols[i]).attr('checked',found ? 1 : 0);
    }
  }

  this.listmenu[show?'show':'hide']();

  if (show) {
    var maxheight=0;
    $('#listmenu fieldset').each(function() {
      var height = $(this).height();
      if (height > maxheight) {
        maxheight = height;
      }
    });
    $('#listmenu fieldset').css("min-height", maxheight+"px")
    // IE6 complains if you set this attribute using either method:
    //$('#listmenu fieldset').css({'height':'auto !important'});
    //$('#listmenu fieldset').css("height","auto !important");
      .height(maxheight);
  };
},

open_listmenu: function(e)
{
  this.show_listmenu();
},

save_listmenu: function()
{
  this.show_listmenu();

  var sort = $('input[name="sort_col"]:checked').val();
  var ord = $('input[name="sort_ord"]:checked').val();
  var thread = $('input[name="view"]:checked').val();
  var cols = $('input[name="list_col[]"]:checked')
    .map(function(){ return this.value; }).get();

  cmail.set_list_options(cols, sort, ord, thread == 'thread' ? 1 : 0);
},

body_mouseup: function(evt, p)
{
  var target = crystal_event.get_target(evt);

  if (this.markmenu && this.markmenu.is(':visible') && target != crystal_find_object('markreadbutton'))
    this.show_markmenu(false);
  else if (this.messagemenu && this.messagemenu.is(':visible') && target != crystal_find_object('messagemenulink'))
    this.show_messagemenu(false);
  else if (this.dragmessagemenu && this.dragmessagemenu.is(':visible') && !crystal_mouse_is_over(evt, crystal_find_object('dragmessagemenu')))
    this.dragmessagemenu.hide();
  else if (this.groupmenu &&  this.groupmenu.is(':visible') && target != crystal_find_object('groupactionslink'))
    this.show_groupmenu(false);
  else if (this.listmenu && this.listmenu.is(':visible') && target != crystal_find_object('listmenulink')) {
    var menu = crystal_find_object('listmenu');
    while (target.parentNode) {
      if (target.parentNode == menu)
        return;
      target = target.parentNode;
    }
    this.show_listmenu(false);
  }
  else if (this.searchmenu && this.searchmenu.is(':visible') && target != crystal_find_object('searchmod')) {
    var menu = crystal_find_object('searchmenu');
    while (target.parentNode) {
      if (target.parentNode == menu)
        return;
      target = target.parentNode;
    }
    this.show_searchmenu(false);
  }
},

body_keypress: function(evt, p)
{
  if (crystal_event.get_keycode(evt) == 27) {
    for (var k in this.popupmenus) {
      if (this[k] && this[k].is(':visible'))
        this[k].hide();
    }
  }
}

};

var cmail_ui;

function crystal_init_mail_ui()
{
  cmail_ui = new crystal_mail_ui();
  crystal_event.add_listener({ object:cmail_ui, method:'body_mouseup', event:'mouseup' });
  crystal_event.add_listener({ object:cmail_ui, method:'body_keypress', event:'keypress' });
  if (cmail.env.task == 'mail') {
    cmail.addEventListener('menu-open', 'open_listmenu', cmail_ui);
    cmail.addEventListener('menu-save', 'save_listmenu', cmail_ui);
    cmail.gui_object('message_dragmenu', 'dragmessagemenu');
  }
}
