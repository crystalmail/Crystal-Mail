/* Accounts interface */

function accounts_validate(){

    var input_dn = rcube_find_object('account_dn');
    var input_id = rcube_find_object('account_id');
    var input_pw = rcube_find_object('account_pw');
    var input_pw_conf = rcube_find_object('account_pw_conf');
    var input_host = rcube_find_object('account_host');
    var input_add = rcube_find_object('add');
 
    if(input_dn.value == ""){
      parent.rcmail.display_message(rcmail.gettext('dnempty','accounts'), 'error');    
      input_dn.focus();
      return false;    
    }
    if(input_id.value == ""){
      parent.rcmail.display_message(rcmail.gettext('userempty','accounts'), 'error');    
      input_id.focus();
      return false;    
    }
    if(input_pw.value == "" && input_add.value == 1){
      parent.rcmail.display_message(rcmail.gettext('passwordempty','accounts'), 'error');    
      input_pw.focus();
      return false;    
    }
    if(input_pw.value != input_pw_conf.value){
      parent.rcmail.display_message(rcmail.gettext('passwordnotmatch','accounts'), 'error');    
      input_pw_conf.focus();
      return false;    
    }
    if(input_host.value == ""){
      parent.rcmail.display_message(rcmail.gettext('hostempty','accounts'), 'error');    
      input_pw.focus();
      return false;    
    }
    return true;    
}

function switch_account(id){

    document.location.href="./?_task=mail&_action=plugin.accounts&_mbox=INBOX&_switch=" + id;
}