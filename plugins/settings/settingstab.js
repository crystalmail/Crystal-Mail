// Settingstab Account

if (window.rcmail) {
  rcmail.addEventListener('init', function(evt) {
    var tab = $('<span>').attr('id', 'settingstabaccount').addClass('tablink');   
    var button = $('<a>').attr('href', rcmail.env.comm_path+'&_action=plugin.account').html(rcmail.gettext('account','settings')).appendTo(tab);
    button.bind('click', function(e){ return rcmail.command('plugin.account', this) });

    // add button and register commands
    rcmail.add_element(tab, 'tabs');
    rcmail.register_command('plugin.account', function() { rcmail.goto_url('plugin.account') }, true);     

  }
)}