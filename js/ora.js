
$(document).ready(function () {


  setInterval(function(){
    if($.cookie("fileLoading")) {
      // clean the cookie for future downoads
      $.removeCookie("fileLoading");

      //redirect
      loading_hide();
    }
  },1000);

  var header = $('#tab_menu');
  var header2 = $('#top_menu');
  var menu_offset = 100; //number of pixels before modifying styles
  var range = 100;

  $(document).scroll(function(event){
    //didScroll = true;
    check_tab_menu();
    var scrollTop = $(this).scrollTop();
    var offset = header.offset().top;
    var height = header.outerHeight();
    offset = offset + height / 2;
    //var calc = 1 - (scrollTop - offset + range) / range;
    var calc = 1 - ((scrollTop - offset + range) / range )*0.5; //set to 0.5 opacity on scroll down
    header.css({ 'opacity': calc });
    header2.css({ 'opacity': calc });

    if ( calc > 1 ) {
      header.css({ 'opacity': 1 });
    } else if ( calc < 0.5 ) {
      header.css({ 'opacity': 0.5 });
    }

  });

  function check_tab_menu() {
    if ($(document).scrollTop() > menu_offset) {
      if($.mobile.activePage.attr('id')=="p1")
      {
        $('#tab_menu').addClass('fixed');
      }
    } else {
      $('#tab_menu').removeClass('fixed');
    }
  }

  $("#textbox1").wijinputdate(
      {
          showTrigger: true
  });

  $("#textbox2").wijinputdate(
      {
          showTrigger: true
  });

  $("#textbox3").wijinputdate(
      {
          showTrigger: true
  });

  $("#textbox4").wijinputdate(
      {
          showTrigger: true
  });

  $("#textbox5").wijinputdate(
      {
          showTrigger: true
  });
  $("#textbox6").wijinputdate(
      {
          showTrigger: true
  });

  $("#textbox7").wijinputdate(
      {
          showTrigger: true
  });
  $("#textbox8").wijinputdate(
      {
          showTrigger: true
  });

  $("#textbox9").wijinputdate(
      {
          showTrigger: true
  });

  $(window).on('resize', function(){
    var win = $(this); //this = window
    $("#top_menu").width(win.width());
          /* if (win.height() >= 820) {  }
          if (win.width() >= 1280) {  } */
  });


  function fnOpenNormalDialog() {
      $("#dialog-confirm").html("Do you have any expenses for this date?");

      // Define the Dialog and its properties.
      $("#dialog-confirm").dialog({
          resizable: false,
          modal: true,
          title: "Expense Reports",
          height: 250,
          width: 400,
          buttons: {
                "Yes": function () {
                  $(this).dialog('close');
                  callback(true);
              },
                  "No": function () {
                  $(this).dialog('close');
                  callback(false);
              }
          }
      });
  }


  $('#btnOpenDialog').click(fnOpenNormalDialog);

  $( "#textbox1" ).change(function() {
    //alert( "date changed" );
  });

  function callback(value) {
    var url = $("").html();
      if (value == true) {
          //alert("Yes");
    //window.location = "";
    start_expense();
      }
      else {
          //alert("No");
      }
  }

  //fnOpenNormalDialog();
});

function calcHeight()
{
  //find the height of the internal page
  var the_height=document.getElementById('expense_frame').contentWindow.document.body.scrollHeight;
  //change the height of the iframe
  document.getElementById('expense_frame').height=the_height;
}

var state = 1;
var request_alert_counter=0;

$('.multi-field-wrapper').each(function() {
    var $wrapper = $('.multi-fields', this);
    $(".add-field", $(this)).click(function(e) {
  $('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('[type=text]').val('');;
  //$('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('select').val('').focus();

    });
    $('.multi-field .remove-field', $wrapper).click(function() {
        if ($('.multi-field', $wrapper).length > 1)
            $(this).parent('.multi-field').remove();
    });
});


function set_time() {
  $( "#entry" ).focus();
  document.getElementById("date1").value = document.getElementById("textbox1").value;
  document.getElementById("date2").value = document.getElementById("textbox1").value;
}

function setCookies(user,pass){
  $.cookie("remember_me", user, {expires: 30});
  $.cookie("key", pass, {expires: 30});
}

function clearCookies(){
  $.removeCookie("remember_me");
  $.removeCookie("key");
}

function reset_request_alert_counter() {
    request_alert_counter=0;
    $("#request_alert").html(request_alert_counter);
    $("#request_alert_div").css("display","none");
}

function setNumberDecimal(event) {
    this.value = parseFloat(this.value).toFixed(2);
}

function setEmployeeList(name, value)
{
      var x = document.getElementById("employee");
      var option = document.createElement("option");
      option.value = value;
      option.text = name;
      x.add(option);
}

function user_moveRight() {
    //var selItem = document.forms[0].leftList.selectedIndex;
    var selItem = document.getElementById("user_leftList").selectedIndex;
    var select1 = document.getElementById("user_leftList");
    var select2 = document.getElementById("user_rightList");
    
    if (selItem == -1) {
        window.alert("You must first select an item on the left side.")
    } 
    else {
      // Remember selected items.
      var is_selected = [];
      for (var i = 0; i < select1.options.length; ++i)
      {
          is_selected[i] = select1.options[i].selected;
      }

      // Remove selected items.
      i = select1.options.length;
      while (i--)
      {
          var opt = document.createElement("option");
          opt.value = select1.options[i].value;
          opt.text = select1.options[i].text;
          if (is_selected[i])
          {
              select2.add(opt);
              select1.remove(i);
          }
      }
    }
}

function study_moveRight() {
    //var selItem = document.forms[0].leftList.selectedIndex;
    var selItem = document.getElementById("study_leftList").selectedIndex;
    var select1 = document.getElementById("study_leftList");
    var select2 = document.getElementById("study_rightList");
    
    if (selItem == -1) {
        window.alert("You must first select an item on the left side.")
    } 
    else {
      // Remember selected items.
      var is_selected = [];
      for (var i = 0; i < select1.options.length; ++i)
      {
          is_selected[i] = select1.options[i].selected;
      }

      // Remove selected items.
      i = select1.options.length;
      while (i--)
      {
          var opt = document.createElement("option");
          opt.value = select1.options[i].value;
          opt.text = select1.options[i].text;
          if (is_selected[i])
          {
              select2.add(opt);
              select1.remove(i);
          }
      }
    }
}

function activity1_moveRight() {
    //var selItem = document.forms[0].leftList.selectedIndex;
    var selItem = document.getElementById("activity1_leftList").selectedIndex;
    var select1 = document.getElementById("activity1_leftList");
    var select2 = document.getElementById("activity1_rightList");
    
    if (selItem == -1) {
        window.alert("You must first select an item on the left side.")
    } 
    else {
      // Remember selected items.
      var is_selected = [];
      for (var i = 0; i < select1.options.length; ++i)
      {
          is_selected[i] = select1.options[i].selected;
      }

      // Remove selected items.
      i = select1.options.length;
      while (i--)
      {
          var opt = document.createElement("option");
          opt.value = select1.options[i].value;
          opt.text = select1.options[i].text;
          if (is_selected[i])
          {
              select2.add(opt);
              select1.remove(i);
          }
      }
    }
}

function activity2_moveRight() {
    //var selItem = document.forms[0].leftList.selectedIndex;
    var selItem = document.getElementById("activity2_leftList").selectedIndex;
    var select1 = document.getElementById("activity2_leftList");
    var select2 = document.getElementById("activity2_rightList");
    
    if (selItem == -1) {
        window.alert("You must first select an item on the left side.")
    } 
    else {
      // Remember selected items.
      var is_selected = [];
      for (var i = 0; i < select1.options.length; ++i)
      {
          is_selected[i] = select1.options[i].selected;
      }

      // Remove selected items.
      i = select1.options.length;
      while (i--)
      {
          var opt = document.createElement("option");
          opt.value = select1.options[i].value;
          opt.text = select1.options[i].text;
          if (is_selected[i])
          {
              select2.add(opt);
              select1.remove(i);
          }
      }
    }
}

function user_moveLeft() {
    var selItem = document.getElementById("user_rightList").selectedIndex;
    var select1 = document.getElementById("user_leftList");
    var select2 = document.getElementById("user_rightList");
    
    if (selItem == -1) {
        window.alert("You must first select an item on the right side.")
    } 
    else {
      // Remember selected items.
      var is_selected = [];
      for (var i = 0; i < select2.options.length; ++i)
      {
          is_selected[i] = select2.options[i].selected;
      }

      // Remove selected items.
      i = select2.options.length;
      while (i--)
      {
      var opt = document.createElement("option");
          opt.value = select2.options[i].value;
      opt.text = select2.options[i].text;
      if (is_selected[i])
      {
        select1.add(opt);
        select2.remove(i);
      }
      }
    }
}

function study_moveLeft() {
    var selItem = document.getElementById("study_rightList").selectedIndex;
    var select1 = document.getElementById("study_leftList");
    var select2 = document.getElementById("study_rightList");
    
    if (selItem == -1) {
        window.alert("You must first select an item on the right side.")
    } 
    else {
      // Remember selected items.
      var is_selected = [];
      for (var i = 0; i < select2.options.length; ++i)
      {
          is_selected[i] = select2.options[i].selected;
      }

      // Remove selected items.
      i = select2.options.length;
      while (i--)
      {
      var opt = document.createElement("option");
          opt.value = select2.options[i].value;
      opt.text = select2.options[i].text;
      if (is_selected[i])
      {
        select1.add(opt);
        select2.remove(i);
      }
      }
    }
}

function activity1_moveLeft() {
    var selItem = document.getElementById("activity1_rightList").selectedIndex;
    var select1 = document.getElementById("activity1_leftList");
    var select2 = document.getElementById("activity1_rightList");
    
    if (selItem == -1) {
        window.alert("You must first select an item on the right side.")
    } 
    else {
      // Remember selected items.
      var is_selected = [];
      for (var i = 0; i < select2.options.length; ++i)
      {
          is_selected[i] = select2.options[i].selected;
      }

      // Remove selected items.
      i = select2.options.length;
      while (i--)
      {
      var opt = document.createElement("option");
          opt.value = select2.options[i].value;
      opt.text = select2.options[i].text;
      if (is_selected[i])
      {
        select1.add(opt);
        select2.remove(i);
      }
      }
    }
}

function activity2_moveLeft() {
    var selItem = document.getElementById("activity2_rightList").selectedIndex;
    var select1 = document.getElementById("activity2_leftList");
    var select2 = document.getElementById("activity2_rightList");
    
    if (selItem == -1) {
        window.alert("You must first select an item on the right side.")
    } 
    else {
      // Remember selected items.
      var is_selected = [];
      for (var i = 0; i < select2.options.length; ++i)
      {
          is_selected[i] = select2.options[i].selected;
      }

      // Remove selected items.
      i = select2.options.length;
      while (i--)
      {
      var opt = document.createElement("option");
          opt.value = select2.options[i].value;
      opt.text = select2.options[i].text;
      if (is_selected[i])
      {
        select1.add(opt);
        select2.remove(i);
      }
      }
    }
}

function setReportEmployeeList(name, value)
{
      var x = document.getElementById("user_leftList");
      var option = document.createElement("option");
      option.value = value;
      option.text = name;
      x.add(option);
}

function setReportStudyList(name, value)
{
      var x = document.getElementById("study_leftList");
      var option = document.createElement("option");
      option.value = value;
      option.text = name;
      x.add(option);
}

function setReportActivity1List(name, value)
{
      var x = document.getElementById("activity1_leftList");
      var option = document.createElement("option");
      option.value = value;
      option.text = name;
      x.add(option);
}

function setReportActivity2List(name, value)
{
      var x = document.getElementById("activity2_leftList");
      var option = document.createElement("option");
      option.value = value;
      option.text = name;
      x.add(option);
}

function resetReportEmployeeList()
{
  $('#user_leftList')
      .find('option')
      .remove()
      .end();
  $('#user_rightList')
      .find('option')
      .remove()
      .end();
      //.append('<option disabled selected value> -- select an employee -- </option>');
}

function resetReportStudyList()
{
  $('#study_leftList')
      .find('option')
      .remove()
      .end();
  $('#study_rightList')
      .find('option')
      .remove()
      .end();
      //.append('<option disabled selected value> -- select an employee -- </option>');
}

function resetReportActivity1List()
{
  $('#activity1_leftList')
      .find('option')
      .remove()
      .end();
  $('#activity1_rightList')
      .find('option')
      .remove()
      .end();
      //.append('<option disabled selected value> -- select an employee -- </option>');
}

function resetReportActivity2List()
{
  $('#activity2_leftList')
      .find('option')
      .remove()
      .end();
  $('#activity2_rightList')
      .find('option')
      .remove()
      .end();
      //.append('<option disabled selected value> -- select an employee -- </option>');
}

function setStudyList(name, value)
{
      var x = document.getElementById("study");
      var option = document.createElement("option");
      option.value = value;
      option.text = name;
      x.add(option);
}

function setActivity1List(name, value)
{
      var x = document.getElementById("activity1");
      var option = document.createElement("option");
      option.value = value;
      option.text = name;
      x.add(option);
}

function setActivity2List(name, value)
{
      var x = document.getElementById("activity2");
      var option = document.createElement("option");
      option.value = value;
      option.text = name;
      x.add(option);
}

function resetEmployeeList()
{
  $('#employee')
      .find('option')
      .remove()
      .end();
      //.append('<option disabled selected value> -- select an employee -- </option>');
}

function resetStudyList()
{
  $('#study')
      .find('option')
      .remove()
      .end()
      .append('<option disabled selected value> -- select an Action-- </option>');
}

function resetActivity1List()
{
  $('#activity1')
      .find('option')
      .remove()
      .end()
      .append('<option disabled selected value> -- select an Activity -- </option>');
}

function resetActivity2List()
{
  $('#activity2')
      .find('option')
      .remove()
      .end()
      .append('<option disabled selected value> -- select an Activity -- </option>');
}

function resetHistory()
{
  $(".history").html('<div id="history" style="display:none" width="100%"><span class="date"></span><span class="study"></span><span class="activity1"></span><span class="activity2"></span><span class="history_notes"></span><span class="hours"></span><span style="margin-right:7px">hours</span><input type="button" class="btn edit_history_button" onclick="editEntry(this)" value="Edit"><button type="button" class="btn" style="display:none">Delete</button></div>');
  $(".requests_history").html('<div id="requests_history" style="display:none" width="100%"><span style="margin-left:10px;padding-left:10px" class="type"></span><span class="start_date"></span><span class="end_date"></span><span class="hours"></span><span class="note"></span><span class="status"></span><button class="btn edit_request_button" onclick="editRequest(this)">Edit</button><button type="button" class="btn" style="display:none">Delete</button></div>');
  $(".admin_requests_history").html('<div id="admin_requests_history" style="display:none;padding:4px" width="100%"><span class="username" style="padding-right:15px"></span><button type="button" class="btn" onclick="approveRequest(this)">Approve</button><button type="button" style="color:red;margin-left:5px" class="btn" onclick="rejectRequest(this)">Reject</button><span style="margin-left:10px;padding-left:10px" class="type"></span><span class="start_date"></span><span class="end_date"></span><span class="hours"></span><span class="note"></span><span class="status"></span><button type="button" class="btn" style="display:none">Delete</button></div>');
}

function resetMessage()
{
  $(".message").html('<div id="message" style="display:none;color:red" width="100%">Please enter your hours for <span class="message_date"></span></div>');
}


function reset()
{
  //$("input[name=day_1]").val("");
}

function hideNoHistory()
{
  $( "#nohistory" ).hide();
}

function changeDate()
{
  change_state(2);
  //var userid = document.getElementById("user");
  //alert(userid.value);
}

function check_state()
{
  return state;
}

function state2()
{

  resetRequest();
  $("#back_button").hide();
  $("#textbox1_holder").show();
  $("#textbox1_holder").css("visibility","visible");
  $(".form").show();
  $("#main").show();
  $("#request_div").hide();
  $("#next_btn").hide();
  $("#title").html("Add entries to your log");  
}


function state3()
{
  $("#back_button").show();
  $("#textbox1_holder").hide();
  $("#textbox1_holder").css("visibility","hidden");
  $("#request_div").show();
  $("#next_btn").hide();
  $("#main").hide();
  $("#title").html("Request Time Off"); 
}



function reset_date(now)
{
  document.getElementById("textbox1").value = now;
}

function reset_entry()
{
  document.getElementById("entry").value = "";
  document.getElementById("ID").value = "0";
  document.getElementById("save_notes").value = "";
}

function start_expense()
{
  document.getElementById("date_expense").value = document.getElementById("date2").value;
  document.getElementById("user_email_expense").value = document.getElementById("user_email").value;
  document.getElementById("user_login_expense").value = document.getElementById("user").value;
  document.getElementById("expense_submit").click();  
}


function set_expense_url(url)
{
  window.open(url,'_blank');  
}

function click_expense(){
  $('#btnOpenDialog').click();
}



function change_state(val)
{
  state = val;
  document.getElementById("state_id").value = check_state();
  document.getElementById("state_username").value = document.getElementById("user").value;
  document.getElementById("state_submit").click();  
}


function start_request(type){
  document.getElementById("activity_request").value = type;
  if(type=='Vacation'){
    $("#textbox2").show("slow");
    $("#textbox3").show("slow");
    $("#hours_request").hide("slow");
    $("#hours_request_title").hide("slow");
    $("#start_date").val("Start Date");
    $("#end_date").val("End Date");
  }
  else if(type=='Personal'){
    $("#textbox2").show("slow");
    $("#hours_request").show("slow");
    $("#hours_request_title").show("slow");
    $("#textbox3").hide("slow");
    $("#start_date").val("Day");
    $("#end_date").val("");
  } 
  else{
    $("#textbox2").show("slow");
    $("#textbox3").hide("slow");
    $("#hours_request").show("slow");
    $("#hours_request_title").show("slow");
    $("#start_date").val("Day");
    $("#end_date").val("");
  } 
  change_state(3);
}


function set_history(num)
{
  if(num == '1'){
    document.getElementById("history_end").value = $("#textbox7").val();
    document.getElementById("history_start").value = $("#textbox6").val();
  }
  if(num == '2'){
    document.getElementById("history_end").value = $("#textbox9").val();
    document.getElementById("history_start").value = $("#textbox8").val();
  }
    document.getElementById("history_flag").value = num;
    //alert(document.getElementById("user").value);
    document.getElementById("history_username").value = document.getElementById("user").value;
    document.getElementById("history_submit").click();  
}


function set_message(date){
  $("#message").clone().insertAfter($( "#message" )).each(function() { 
                $(this).children('.message_date').html(date);
                $(this).show();
                 });
}

function set_users(id,name){
  $("#approve_requests_table").clone().prop('id', '' ).insertAfter($( "#approve_requests_table" )).each(function() { 
                $('tr', this).find("td:first").children('input').val(name);
                $('tr', this).find("td:nth-child(2)").children('input').val(id);
                $(this).show();
                 });
}




function set_profile(dir,last_login,vacation,vacation_used,personal_time){
  document.getElementById("edit_profile_user").value = document.getElementById("user").value;
  document.getElementById("user_request").value = document.getElementById("user").value;
  document.getElementById("Director").value = dir;
  document.getElementById("last_login").value = last_login;
  document.getElementById("vacation").value = vacation;
  document.getElementById("vacation_used").value = vacation_used;
  document.getElementById("personal_time").value = personal_time;
  $("#vacation_used_span").html(vacation_used);
  $("#vacation_span").html(vacation);
  $("#personal_time_span").html(personal_time);
  
}

function resetVacation(vacation,vacation_used,personal_time){
  if(vacation!='none')
  {
    document.getElementById("vacation").value = vacation;
    document.getElementById("vacation_used").value = vacation_used;
    $("#vacation_used_span").html(vacation_used);
    $("#vacation_span").html(vacation);
  }
  if(personal_time!='none')
  {
    document.getElementById("personal_time").value = personal_time;
    $("#personal_time_span").html(personal_time);
  }
}

function submit()
{
  loading_show();
  document.getElementById("date1").value = document.getElementById("textbox1").value;
  document.getElementById("date2").value = document.getElementById("textbox1").value;
  document.getElementById("save_submit").click();
    
}


function setActivity1(el)
{
  var stud = $(el).val();
  document.getElementById("set_activity1_study").value = stud;
  document.getElementById("set_activity1_director").value = document.getElementById("Director").value;
  document.getElementById("set_activity1_submit").click();

    
}


function setActivity2(el)
{
  var activity1 = $(el).val();
  document.getElementById("set_activity2_activity1").value = activity1;
  document.getElementById("set_activity2_study").value = document.getElementById("set_activity1_study").value;
  document.getElementById("set_activity2_submit").click();  
}

function setUserAdmin(el)
{
  var user = $(el).val();
  document.getElementById("setAdminUser").value = user;
  window.location.href='http://10.35.0.235/ora/php/admin_user.php?admin=' + user;
  //document.getElementById("set_admin_submit").click();
    
}

function setAdmin(admin){
  document.getElementById("administrator").value = admin;
}


function send_request(num)
{
  //if(num!=1){
  loading_show();
  //}
  selectall_report();
  document.getElementById("action_request").value = num;
  document.getElementById("user_request_reports").value = document.getElementById("user").value;
  document.getElementById("approve_requests_submit").click();
}

function loading_show()
{
  $('#loading_div').show();   
}

function loading_hide()
{
  $('#loading_div').hide();
}

function cancel_request()
{
  document.getElementById("cancel").value = '1';
  document.getElementById("request_submit").click();
  resetRequest();
}

function cancel_update()
{
  document.getElementById("save_cancel").style.display = 'none';
  document.getElementById("ID").value = "0";
}

function cancel_update_show()
{
  document.getElementById("save_cancel").style.display = 'block';
}

function cancel_update_hide()
{
  document.getElementById("save_cancel").style.display = 'none';
  document.getElementById("ID").value = "0";
}


function setRequest(el)
{
  var request = $(el).val();
  if(request=='Vacation'){
    $("#textbox2").show("slow");
    $("#textbox3").show("slow");
    $("#hours_request").hide("slow");
    $("#hours_request_title").hide("slow");
    $("#start_date").val("Start Date");
    $("#end_date").val("End Date");
  }
  else if(request=='Personal'){
    $("#textbox2").show("slow");
    $("#hours_request").show("slow");
    $("#hours_request_title").show("slow");
    $("#textbox3").hide("slow");
    $("#start_date").val("Day");
    $("#end_date").val("");
  } 
  else{
    $("#textbox2").show("slow");
    $("#textbox3").hide("slow");
    $("#hours_request").show("slow");
    $("#hours_request_title").show("slow");
    $("#start_date").val("Day");
    $("#end_date").val("");
  } 
}


function showEmployeeTable(){

  document.getElementById("addUsers").style.visibility = "visible";
  document.getElementById("addUsers").style.display = "block";


}

function showRequestsTable(){

  document.getElementById("requests").style.visibility = "visible";
  document.getElementById("requests").style.display = "block";

}


function addHistory(date,study,activity1,activity2,hours,id,approved,notes){
  $("#history").clone().prop('id', id ).insertAfter($( "#history" )).each(function() { 
                $(this).children('.date').html(date);
                $(this).children('.study').html(' - '+study);
                $(this).children('.activity1').html(' - '+activity1);
                if(activity2 != ""){
                  $(this).children('.activity2').html(' - '+activity2);
                }
                $(this).children('.hours').html(' - '+hours+' ');
                if(notes != ""){
                  $(this).children('.history_notes').html(' - '+notes);
                }
                if(approved == '1'){
                  $(this).children('.edit_history_button').hide();
                }
                $(this).show();

/*****************************************************************
                else{
                  $(this).show();
                }
******************************************************************/
                 });
}


function addRequestHistory(type,start_date,end_date,hours,status,id,note){
  $("#requests_history").clone().prop('id', id ).insertAfter($( "#requests_history" )).each(function() { 
                $(this).children('.type').html(type);
                $(this).children('.start_date').html(' - '+start_date);
                if(end_date != "0000-00-00"){
                  $(this).children('.end_date').html(' to '+end_date);
                }
                if(hours != "0"){
                  $(this).children('.hours').html(' - ' + hours + ' hours');
                }
                var slim_note = note.slice(0, 15);
                if(slim_note != ""){
                  if(slim_note.length == 15){
                    $(this).children('.note').html(' note: ' + slim_note + '...');
                  }
                  else{ $(this).children('.note').html(' note: ' + slim_note); }
                }
                if(status == '<font color="green"><b>APPROVED</b></font>')
                {
                  $(this).children('.edit_request_button').hide();
                }
                $(this).children('.status').html(' - '+status);
                $(this).show();


/************************************************************

                else{
                  $(this).show();
                }
***********************************************************/
                 });
}


function addRequestHistory_Admin(type,start_date,end_date,hours,status,id,note,username,company){
  $("#admin_requests_history").clone().prop('id', id ).insertAfter($( "#admin_requests_history" )).each(function() { 
                $(this).children('.type').html(type);
                $(this).children('.start_date').html(' - '+start_date);
                if(end_date != "0000-00-00"){
                  $(this).children('.end_date').html(' to '+end_date);
                }
                if(hours != "0"){
                  $(this).children('.hours').html(' - ' + hours + ' hours');
                }
                //var slim_note = note.slice(0, 15);
                if(note != ""){
                  $(this).children('.note').html(' note: ' + note);
                }
                $(this).children('.status').html(' - '+status);
                $(this).children('.username').html(username);
                //$(this).show();
                
                if( (company!=$('#company').val()) ||(status=='<font color="green"><b>APPROVED</b></font>' || status=='<font color="red"><b>REJECTED</b></font>') )
                {
                  $(this).hide();
                }
                else{
                  $(this).show();
                  request_alert_counter++;
                  $("#request_alert").html(request_alert_counter);
                  if($('#administrator').val()!=""){
                    $("#request_alert_div").css("display","inline-block");
                  }
                }

                 });
}


function setChecked(el){

  var ID = $(el).parent().prev().children('input').val();
  if(ID=='0'){
    $(el).parent().prev().children('input').val('1');
  }
  else{
    $(el).parent().prev().children('input').val('0');
  }
  //alert($(el).parent().prev().children('input').val());
}

function setChecked_UserReport(el){

  var ID = $(el).parent().prev().children('input').val();
  if(ID=='0'){
    $(el).parent().prev().children('input').val('1');
    $("#tbl_UserReport").show();
  }
  else{
    $(el).parent().prev().children('input').val('0');
    $("#tbl_UserReport").hide();
  }
  //alert($(el).parent().prev().children('input').val());
}

function setChecked_StudyReport(el){

  var ID = $(el).parent().prev().children('input').val();
  if(ID=='0'){
    $(el).parent().prev().children('input').val('1');
    $("#tbl_StudyReport").show();
  }
  else{
    $(el).parent().prev().children('input').val('0');
    $("#tbl_StudyReport").hide();
  }
  //alert($(el).parent().prev().children('input').val());
}

function setChecked_Activity1Report(el){

  var ID = $(el).parent().prev().children('input').val();
  if(ID=='0'){
    $(el).parent().prev().children('input').val('1');
    $("#tbl_Activity1Report").show();
  }
  else{
    $(el).parent().prev().children('input').val('0');
    $("#tbl_Activity1Report").hide();
  }
  //alert($(el).parent().prev().children('input').val());
}

function setChecked_Activity2Report(el){

  var ID = $(el).parent().prev().children('input').val();
  if(ID=='0'){
    $(el).parent().prev().children('input').val('1');
    $("#tbl_Activity2Report").show();
  }
  else{
    $(el).parent().prev().children('input').val('0');
    $("#tbl_Activity2Report").hide();
  }
  //alert($(el).parent().prev().children('input').val());
}

function selectall_report(){ 
  selectBox = document.getElementById("user_rightList");
  for (var i = 0; i < selectBox.options.length; i++) 
  { 
    selectBox.options[i].selected = true; 
  }
  selectBox2 = document.getElementById("study_rightList");
  for (var i = 0; i < selectBox2.options.length; i++) 
  { 
    selectBox2.options[i].selected = true; 
  }
  selectBox3 = document.getElementById("activity1_rightList");
  for (var i = 0; i < selectBox3.options.length; i++) 
  { 
    selectBox3.options[i].selected = true; 
  }
  selectBox4 = document.getElementById("activity2_rightList");
  for (var i = 0; i < selectBox4.options.length; i++) 
  { 
    selectBox4.options[i].selected = true; 
  }
  selectBox = document.getElementById("user_leftList");
  for (var i = 0; i < selectBox.options.length; i++) 
  { 
    selectBox.options[i].selected = true; 
  }
  selectBox2 = document.getElementById("study_leftList");
  for (var i = 0; i < selectBox2.options.length; i++) 
  { 
    selectBox2.options[i].selected = true; 
  }
  selectBox3 = document.getElementById("activity1_leftList");
  for (var i = 0; i < selectBox3.options.length; i++) 
  { 
    selectBox3.options[i].selected = true; 
  }
  selectBox4 = document.getElementById("activity2_leftList");
  for (var i = 0; i < selectBox4.options.length; i++) 
  { 
    selectBox4.options[i].selected = true; 
  }
}

function selectall(el){
  $(el).parent().prev().children('input').val('1');
}

function selectnone(el){
  $(el).parent().prev().children('input').val('0');
}

function approveRequest(el){
  loading_show();
  var ID = $(el).parent().attr("id");
  document.getElementById("approve_timeoff_admin").value = document.getElementById("administrator").value;
  document.getElementById("approve_timeoff_user").value = document.getElementById("user").value;
  document.getElementById("approve_timeoff_id").value = ID;
  document.getElementById("approve_timeoff_submit").click();
  //alert(ID);
}

function rejectRequest(el){
  loading_show();
  var ID = $(el).parent().attr("id");
  document.getElementById("reject_timeoff_user").value = document.getElementById("user").value;
  document.getElementById("reject_timeoff_id").value = ID;
  document.getElementById("reject_timeoff_submit").click();
  //alert(ID);
}

function editEntry(el){
  scrollToMain();
  //change_state(2);
  state2();
  resetRequest();
  var ID = $(el).parent().attr("id");
  document.getElementById("set_entry_id").value = ID;
  document.getElementById("set_entry_submit").click();
  //alert(ID);
}

function editRequest(el){
  state3();
  var ID = $(el).parent().attr("id");
  document.getElementById("set_request_id").value = ID;
  document.getElementById("set_request_submit").click();
  //alert(ID);
}


function setEntry(date,byweek,year,id,study,activity1,activity2,hours){
  document.getElementById("textbox1").value = date;
  document.getElementById("date2").value = date;
  document.getElementById("byweek").value = byweek;
  document.getElementById("year").value = year;
  document.getElementById("ID").value = id;
  document.getElementById("study").value = study;
  document.getElementById("activity1").value = activity1;
  document.getElementById("activity2").value = activity2;
  document.getElementById("entry").value = hours;

}

function setRequestEntry(ID,type,start_date,end_date,hours){
  document.getElementById("textbox2").value = start_date;
  document.getElementById("textbox3").value = end_date;
  document.getElementById("ID_request").value = ID;
  document.getElementById("hours_request").value = hours;
  document.getElementById("activity_request").value = type;
  start_request(type);

}

function resetRequest(){
  document.getElementById("textbox2").value = "";
  document.getElementById("textbox3").value = "";
  document.getElementById("ID_request").value = "0";
  document.getElementById("hours_request").value = 0;
  document.getElementById("cancel").value = '0';
  document.getElementById("activity_request").value = "select a request";

}

function checkLogin(){
  document.getElementById("check_login_username").value = document.getElementById("user").value;
  document.getElementById("check_login_submit").click();
}

function Logout(){
  clearCookies();
  document.getElementById("logout_username").value = document.getElementById("user").value;
  document.getElementById("logout_submit").click();
}

function scrollToMain(){
  $("html, body").delay(250).animate({scrollTop: "300px" }, 200);
}


var select_all = document.getElementById("select_all"); //select all checkbox
var checkboxes = document.getElementsByClassName("checkbox"); //checkbox items

//select all checkboxes
select_all.addEventListener("change", function(e){
    for (i = 0; i < checkboxes.length; i++) { 
        checkboxes[i].checked = select_all.checked;
  if(select_all.checked){
    selectall(checkboxes[i]);
  }
  else{
    selectnone(checkboxes[i]);
  }
    }
});


for (var i = 0; i < checkboxes.length; i++) {
    checkboxes[i].addEventListener('change', function(e){ //".checkbox" change 
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if(this.checked == false){
            select_all.checked = false;
        }
        //check "select all" if all checkbox items are checked
        if(document.querySelectorAll('.checkbox:checked').length == checkboxes.length){
            select_all.checked = true;
        }
    });
}
