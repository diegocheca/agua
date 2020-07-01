$(document).on('ready',function()
{
 $('#calendar').fullCalendar({
        //weekends: false
      //  editable: false, // Don't allow editing of events
		handleWindowResize: true,
		weekends: false, // Hide weekends
		defaultView: 'agendaWeek', // Only show week view
		header: false, // Hide buttons/titles
		height : 450,
        width  : 450,
       // lang: 'es-do',
		minTime: '07:30:00', // Start time for the calendar
		maxTime: '22:00:00', // End time for the calendar
		columnFormat: {
		    week: 'ddd' // Only show day of the week names
		},
		// displayEventTime: true, // Display event time
  //       dayClick: function() {
  //           alert('a day has bee puto n clicked!');
  //       }
        events : [
        {
            title  : 'event1',
            start  : '2017-07-01'
        },
        {
            title  : 'event2',
            start  : '2017-07-06',
            end    : '2017-07-07'
        },
        {
            title  : 'event3',
            start  : '2010-01-09 12:30:00',
            allDay : false // will make the time show
        }
    ]
    })
 });//fin document on ready