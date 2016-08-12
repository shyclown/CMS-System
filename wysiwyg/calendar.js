var Calendar = function(oID)
{
  // Often used function to create element
  this._el = function(tag, place){
    var el = document.createElement(tag);
    place.appendChild(el);
    return el;
  }

  // Find element of Input and place new element before it
  // and make input hidden
  this.date_input = document.getElementById(oID);
  this.date_input.type = 'hidden';

  this.wrap = document.createElement('div');
  this.date_input.parentNode.insertBefore(this.wrap,this.date_input);
  // Parse value inside of input if exist
  // and fill it to selected value
  // attach event to listen to changes if input is kept visual - NOT REALISED
  this.input_val = 'empty';
  var values_in_input = this.date_input.value;
  if(this.date_input.value!= ''){ this.input_val = this.date_input.value.split(".");}


  this.calendar_wrap = this._el('div',this.wrap);
  this.calendar_wrap.className = this.css.cal_wrap;
  this.calendar_controls = this._el('div',this.wrap);
  this.calendar_placement = this.calendar_wrap;
  this.days_in_month = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
  // Load current date
  this.curr_date = new Date();
  this.curr_day = this.curr_date.getDate();
  this.curr_month = this.curr_date.getMonth();
  this.curr_year  = this.curr_date.getFullYear();
  // Storing user selected date
  // Default value is current date
  this.selected_day_el = 'none';
  this.selected_day = (this.input_val == 'empty' ? this.curr_day : this.input_val[0]);
  this.selected_month = (this.input_val == 'empty' ? this.curr_month : (parseInt(this.input_val[1])-1));
  this.selected_year = (this.input_val == 'empty' ? this.curr_year : this.input_val[2]);
  // This controls which calendar is currently displayed
  // Default values are taken from current date
  this.displayed_month = this.selected_month;
  this.displayed_year = this.selected_year;
  // build first calendar
  this.calendar_table = '';
  this.generate();
  this.place_input();
  //this.visible_calendar = new this.generate(this.curr_month, this.curr_year);
}

Calendar.prototype.css =
{
  cal_wrap: 'calendar-wrap',
  table: 'calendar-table',
  header_month: 'calendar-header-month',
  header: 'calendar-header',
  header_day: 'calendar-header-day',
  day: 'calendar-day',
  sel_day: 'cal-select',
  cal_btn_row: 'cal-btn-row',
  cal_btn: 'cal-btn',
  cal_fl_right: 'cal-right',
  cal_fl_left: 'cal-left'
}

Calendar.prototype.labels =
{
  days : ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
  months : ['January', 'February', 'March', 'April',
            'May', 'June', 'July', 'August', 'September',
            'October', 'November', 'December'],
  btn_prev : '< previous',
  btn_next : 'next >'
}

Calendar.prototype.control_btn = function(){
    // buttons to change month
    var btn_prev = this._el('div',this.calendar_btns_row);
    var btn_next = this._el('div',this.calendar_btns_row);
    btn_prev.className = this.css.cal_btn;
    btn_prev.classList.add(this.css.cal_fl_left);
    btn_next.className = this.css.cal_btn;
    btn_next.classList.add(this.css.cal_fl_right);
    btn_prev.innerHTML = this.labels.btn_prev;
    btn_next.innerHTML = this.labels.btn_next;
    btn_prev.addEventListener('click',this.load_prev.bind(this),false);
    btn_next.addEventListener('click',this.load_next.bind(this),false);
}

Calendar.prototype.load_next = function(){
  if(this.displayed_month == 11){
    this.displayed_month = 0;
    this.displayed_year++;
  }
  else{
    this.displayed_month++;
  }
  this.calendar_wrap.innerHTML = '';
  this.generate();
}

Calendar.prototype.load_prev = function(){
  if(this.displayed_month == 0){
    this.displayed_month = 11;
    this.displayed_year--;
  }
  else{
    this.displayed_month--;
  }
  this.calendar_wrap.innerHTML = '';
  this.generate();
}

Calendar.prototype.day_event = function()
{
  if(this.selected_day_el != 'none'){  this.selected_day_el.classList.remove(this.css.sel_day); }
  this.selected_day_el = event.target;
  this.selected_day = event.target.innerHTML;
  this.selected_month = this.displayed_month;
  this.selected_year = this.displayed_year;
  this.selected_day_el.classList.add(this.css.sel_day);
  this.place_input();
}

Calendar.prototype.place_input = function(){
  this.date_input.value = this.selected_day +'.'+ (this.selected_month+1) +'.'+ this.selected_year;
}

Calendar.prototype.day_number = function(i, place, calendar)
{
  var el = calendar._el('td', place);
  el.className = calendar.css.day;
  el.addEventListener('click',calendar.day_event.bind(calendar),false);
  el.innerHTML = i;
  return el;
}

Calendar.prototype.day_name = function(i, place, calendar)
{
  var el = calendar._el('td',place);
  el.className = calendar.css.header_day;
  el.innerHTML = calendar.labels.days[i];
  return el;
}

Calendar.prototype.days_nr = function(){
  if (this.displayed_month == 1)
  { // February only!
    if((this.displayed_year % 4 == 0 && this.displayed_year % 100 != 0) || this.displayed_year % 400 == 0){
      return 29;
    }
  }
  return this.days_in_month[this.displayed_month];
}

// this is main function for building HTML of Calendar
Calendar.prototype.generate = function(month, year)
{
  // get first day of month
  var firstDay = new Date(this.displayed_year, this.displayed_month, 1);
  this.startingDay = firstDay.getDay();
  //this.buildNew = this.build.bind(this);
  this.build();
}

Calendar.prototype.build = function()
{
  this.calendar_btns_row = this._el('div', this.calendar_placement);
  this.calendar_btns_row.className = this.css.cal_btn_row;
  this.control_btn();

  var calendar = this;
  var monthLength = this.days_nr();

  /* HTML */
  var calendar_table = this._el('table',this.calendar_placement);
  var header_row = this._el('tr', calendar_table);
  var header_value = this._el('th', header_row);
  var daynames_wrap = this._el('tr',calendar_table);

  calendar_table.className = this.css.table;
  header_value.colSpan = 7;
  header_value.innerHTML = this.labels.months[this.displayed_month] + '&nbsp' + this.displayed_year;
  header_value.className = this.css.header_month;
  daynames_wrap.className = this.css.header;


  // fill in daynames
  var funk = this.day_name.bind(this);
  //console.log(this.startingDay);

  for(var i = 0; i <= 6; i++){ var el = new this.day_name(i, daynames_wrap, calendar);  }
  // set first number to be 1
  var day = 1;
  // Loop weeks
  for ( var i = 0; i < 9; i++){ // this number is bigger - we break loop after all days loaded
    var row = this._el('tr',calendar_table);
    // Loop days

    var first_day;
    for ( var j = 0; j <= 6; j++){
      if(day <= monthLength && (i > 0 || j >= this.startingDay)){

        var el = new this.day_number(day, row, calendar);
        if(el.innerHTML == this.selected_day && this.displayed_month == this.selected_month && this.displayed_year == this.selected_year){
          this.selected_day_el = el;
          el.classList.add(this.css.sel_day);
        }
        first_day = j;
        day++;
      }else{
        var el = this._el('td',row);
      }
    }

    if (day > monthLength) { break; }
  }
  this.calendar_table = calendar_table;
}
