 // For Display Date and Time
 function updateRealTimeDate() {
    var currentDate = new Date();
    var formattedDate = currentDate.toLocaleString('en-US', {year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true });
    // var formattedDate = currentDate.toLocaleString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true });
    document.getElementById('realTimeDate').textContent = formattedDate;
}
setInterval(updateRealTimeDate, 1000);
updateRealTimeDate();